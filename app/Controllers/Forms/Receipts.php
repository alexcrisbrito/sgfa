<?php


namespace App\Controllers\Forms;


use App\Helpers\AdminSession;
use App\Contracts\PrintPdf;
use App\Helpers\UserSession;
use App\Helpers\WorkerSession;
use App\Models\Accounts;
use App\Models\Config;
use App\Models\Invoice;
use App\Controllers\BaseController;
use App\Models\Receipt as ReceiptsModel;
use App\Models\Client;
use App\Contracts\Messages;

class Receipts extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function add($data): void
    {
        $invoice_id = filter_var($data["invoice_id"], FILTER_VALIDATE_INT);
        $amount = filter_var($data["amount"], FILTER_VALIDATE_FLOAT);
        $paid_via = filter_var($data["paid_via"], FILTER_VALIDATE_INT);

        if (!$invoice_id || !$amount || !$paid_via) {
            $this->jsonResult(false, "Preencha todos os campos correctamente !");
            return;
        }

        $invoice = (new Invoice())->find()->where("id = '$invoice_id'")->execute();

        if (!$invoice) {
            $this->jsonResult(false, "A factura referente a este recibo não existe, tente novamente !");
            return;
        }

        if ($invoice->status == 2 || $invoice->status == 3 || $invoice->debt == 0 || $amount > $invoice->debt) {
            $this->jsonResult(false,
                "A factura para qual tentou emitir o recibo está PAGA, CANCELADA ou, o VALOR em dívida é menor que o valor pago");
            return;
        }

        $save = (new ReceiptsModel())->save([
            "client_id" => $invoice->client_id,
            "invoice_id" => $invoice->id,
            "amount" => $amount,
            "paid_via" => $paid_via,
            "processed_by" => WorkerSession::has() ? WorkerSession::get()['id'] : AdminSession::get()['id'] ?? 0
        ])->execute();

        if ($save) {

            $debt = $invoice->debt - $amount;

            (new Invoice())->update(["debt" => $debt, "status" => $debt <= 0 ? 2 : 1])
                ->where("id = '$invoice->id'")->execute();

            $account = (new Accounts())->find()->where("id = '".$paid_via."'")->execute();

            (new Accounts())->update(["balance" => ((float)$account->balance + $amount)])
                ->where("id = '".$account->id."'")->execute();

            if (isset($_SESSION['accounts']))
                $_SESSION['accounts'][$paid_via - 1]['balance'] += $amount;

            if (isset($_SESSION['config']) && $_SESSION['config']['auto_sms'] == 1) {
                $receipt = (new ReceiptsModel())->find()->where("invoice_id = '$invoice_id'")->execute();
                $client = (new Client())->find()->where("id = '$invoice->client_id'")->execute();

                if (Messages::receipt($receipt, $client)) {
                    $this->jsonResult(true,
                        "O recibo no valor de $amount MT para a factura $invoice->id foi emitido com sucesso");
                    return;
                }
            }

            $this->jsonResult(true,
                "O recibo no valor de $amount MT para a factura $invoice->id foi emitido com sucesso");
            return;
        }

        echo ajax("msg", ["type" => "alert-danger", "msg" => "Erro ao processar, tente novamente !"]);
    }

    public function visualize($data): void
    {
        $receipt_id = $data["id"];
        $receipt = (new ReceiptsModel())->find()->where("id = '$receipt_id'")->execute();


        if ($receipt && AdminSession::has() || WorkerSession::has() || (UserSession::has() && UserSession::get()["id"] == $client->id)) {

            $invoice = (new Invoice())->find()->where("id = '$receipt->invoice_id'")->execute();
            $client = (new Client())->find()->where("id = '$invoice->client_id'")->execute();
            $config = (new Config())->find()->execute();

            $receipt->paid_via = explode(";", $config->payment_methods)[$receipt->paid_via - 1];

            echo $this->view->render("docs::receipt", [
                "receipt" => $receipt,
                "invoice" => $invoice,
                "client" => $client
            ]);

            return;
        }

        $this->router->redirect("home");
    }

    public function print($data)
    {
        $receipt_id = $data["id"];
        $receipt = (new ReceiptsModel())->find()->where("id = '$receipt_id'")->execute();

        if ($receipt && ((AdminSession::has() || WorkerSession::has()) ||
            (UserSession::has() && UserSession::get()["id"] == $receipt->client_id))) {

            $invoice = (new Invoice())->find()->where("id = '$receipt->invoice_id'")->execute();
            $client = (new Client())->find()->where("id = '$invoice->client_id'")->execute();
            $config = (new Accounts())->find()->execute(null, true);
            $receipt->paid_via = $config[$receipt->paid_via - 1]->name;

            PrintPdf::receipt($receipt, $client, $invoice);

            return;
        }

        $this->router->redirect("home");
    }

    public function update($data): void
    {
        $receipt_id = filter_var($data["receipt_id"], FILTER_VALIDATE_INT);
        $prop = filter_var($data["prop"], FILTER_SANITIZE_STRING);
        $value = filter_var($data["value"], FILTER_SANITIZE_STRIPPED);

        if (!$receipt_id or !$prop or !$value) {
            echo ajax("msg", ["type" => "danger", "msg" => "Por favor, preencha os campos corretamente."]);
            return;
        }

        $updated = (new ReceiptsModel())->update(["$prop" => $value])->where("id = '{$receipt_id}'")->execute();

        if ($updated) {
            echo ajax("msg", ["type" => "success", "msg" => "Atualizou o recibo com sucesso !"]);
            return;
        }

        echo ajax("msg", ["type" => "warning", "msg" => "Não existe registo do recibo selecionado."]);
    }

    public function delete($data): void
    {
        $receipt_id = filter_var($data["id"], FILTER_VALIDATE_INT);

        $deleted = (new ReceiptsModel())->delete()->where("id = '{$receipt_id}'")->execute();

        if ($deleted) {
            echo ajax("msg", ["type" => "success", "msg" => "Apagou o recibo com sucesso !"]);
            return;
        }

        echo ajax("msg", ["type" => "warning", "msg" => "Não existe registo do recibo selecionado."]);
    }

}