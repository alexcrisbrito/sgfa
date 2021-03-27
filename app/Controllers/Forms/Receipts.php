<?php


namespace App\Controllers\Forms;


use App\Helpers\PrintPdf;
use App\Helpers\UserSession;
use App\Models\Invoice;
use App\Controllers\BaseController;
use App\Models\Receipt as ReceiptsModel;
use App\Models\Client;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Helpers\Messages;

class Receipts extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function add($data): void
    {
        $invoice_id = filter_var($data["invoice_id"], FILTER_SANITIZE_STRING);
        $amount = filter_var($data["amount"], FILTER_VALIDATE_FLOAT);
        $paid_via = filter_var($data["paid_via"], FILTER_SANITIZE_STRING);

        if (!$invoice_id or !$amount or !$paid_via) {
            echo ajax("msg", ["type" => "alert-warning", "msg" => "Por favor, preencha todos os campos corretamente !"]);
            return;
        }

        $invoicesModel = new Invoice();
        $invoice = $invoicesModel->find()->where("id = '{$invoice_id}'")->execute();

        if (!$invoice) {
            echo ajax("msg", ["type" => "alert-danger", "msg" => "A fatura referente a este recibo não existe, tente novamente !"]);
            return;
        }

        if ($invoice->status == 2 || $invoice->status == 3 || $invoice->debt == 0 or $amount > $invoice->debt) {
            echo ajax("msg", ["type" => "alert-danger", "msg" => "A fatura para qual tentou emitir o recibo está PAGA, CANCELADA ou o VALOR em dívida é menor que o valor inserido"]);
            return;
        }

        $saved = (new ReceiptsModel())->save(["invoice_id" => $invoice_id, "amount" => $amount, "paid_via" => $paid_via])
            ->execute();

        if ($saved) {

            $debt = $invoice->debt - $amount;

            if ($debt == 0) {
                $invoicesModel->update(["debt" => $debt, "status" => 2])->where("id = '{$invoice_id}'")->execute();
            } else {
                $invoicesModel->update(["debt" => $debt])->where("id = '{$invoice_id}'")->execute();
            }

            $recibo = (new ReceiptsModel())->find()->where("invoice_id = '{$invoice_id}'")->execute();
            $cliente = (new Client())->find()->where("id = '{$invoice->Cliente}'")->execute();

            $com = Messages::receipt($recibo, $cliente);

            if ($com) {
                echo ajax("msg", ["type" => "alert-success", "msg" => "O recibo para o cliente foi emitido com sucesso e a SMS de aviso foi enviada !"]);
                return;
            }

            echo ajax("msg", ["type" => "alert-warning", "msg" => "O recibo para o cliente foi emitido com sucesso e a SMS de aviso não foi enviada !"]);
            return;
        }

        echo ajax("msg", ["type" => "alert-danger", "msg" => "Erro de sistema, tente novamente !"]);
    }

    /* (!) NOT USED METHOD */
    public function lerRecibo($data): void
    {
        if ($data["receipt_id"]) {

            $receipt_id = filter_var($data["receipt_id"], FILTER_VALIDATE_INT);

            $receipt = (new ReceiptsModel())->find()->where("id = '{$receipt_id}'");

            if ($receipt) {
                echo ajax("data", ["dados" => $receipt]);
                return;
            }

        }

        echo ajax("msg", ["type" => "alert-warning", "msg" => "[!] Sem registo do recibo indicado."]);
    }

    public function visualizeReceipt($data): void
    {
        $receipt_id = $data["receipt_id"];

        $receipt = (new ReceiptsModel())->find()->where("id = '{$receipt_id}'")->execute();
        $invoice = (new Invoice())->find()->where("id = '{$receipt->invoice_id}'")->execute();
        $client = (new Client())->find()->where("id = '{$invoice->client_id}'")->execute();

        if ($invoice && $_SESSION["ID"] == 0 or $_SESSION["ID"] == $invoice->client_id) {
            echo $this->view->render("docs::recibo", [
                "receipt" => $receipt,
                "client" => $client
            ]);

            return;
        }

        echo ajax("msg", ["type" => "alert-danger", "msg" => "[!] A fatura informada não existe, tente novamente."]);
    }

    public function update($data): void
    {
        $receipt_id = filter_var($data["receipt_id"], FILTER_VALIDATE_INT);
        $prop = filter_var($data["prop"], FILTER_SANITIZE_STRING);
        $value = filter_var($data["value"], FILTER_SANITIZE_STRIPPED);

        if (!$receipt_id or !$prop or !$value) {
            echo ajax("msg", ["type" => "danger", "msg" => "[!] Por favor, preencha os campos corretamente."]);
            return;
        }

        $updated = (new ReceiptsModel())->update(["$prop" => $value])->where("id = '{$receipt_id}'")->execute();

        if ($updated) {
            echo ajax("msg", ["type" => "success", "msg" => "Atualizou o recibo com sucesso !"]);
            return;
        }

        echo ajax("msg", ["type" => "warning", "msg" => "[!] Não existe registo do recibo selecionado."]);
    }

    public function delete($data): void
    {
        $receipt_id = filter_var($data["id"], FILTER_VALIDATE_INT);

        $deleted = (new ReceiptsModel())->delete()->where("id = '{$receipt_id}'")->execute();

        if ($deleted) {
            echo ajax("msg", ["type" => "success", "msg" => "Apagou o recibo com sucesso !"]);
            return;
        }

        echo ajax("msg", ["type" => "warning", "msg" => "[!] Não existe registo do recibo selecionado."]);
    }

    public function print($data)
    {
        $receipt_id = $data["receipt_id"];

        $receipt = (new ReceiptsModel())->find()->where("id = '{$receipt_id}'")->execute();
        $invoice = (new Invoice())->find()->where("id = '{$receipt->invoice_id}'")->execute();
        $client = (new Client())->find()->where("id = '{$invoice->client_id}'")->execute();

        if ($receipt && UserSession::get()['id'] == 0 || UserSession::get()["id"] == $client->id) {
            PrintPdf::receipt($receipt, $client);
            exit;
        }

        $this->router->redirect("home");
    }
}