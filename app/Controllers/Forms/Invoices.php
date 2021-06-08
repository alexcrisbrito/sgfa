<?php

namespace App\Controllers\Forms;


use App\Models\Accounts;
use App\Models\Invoice as InvoicesModel;
use DateInterval;
use DateTime;
use App\Helpers\AdminSession;
use App\Contracts\Messages;
use App\Contracts\PrintPdf;
use App\Helpers\UserSession;
use App\Helpers\WorkerSession;
use App\Models\Client;
use App\Controllers\BaseController;
use App\Models\Invoice;


class Invoices extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);

    }

    private function get_operator(): array
    {
        if (AdminSession::has()) {
            $operator = AdminSession::get();
        } else {
            $operator = WorkerSession::get();
        }

        return $operator;
    }


    public function add($data): void
    {
        if (!AdminSession::has() && !WorkerSession::has()) {
            $this->router->redirect("home");
        }

        $client_id = filter_var($data["client_id"], FILTER_SANITIZE_STRING);
        $counter = filter_var($data["counter"], FILTER_VALIDATE_FLOAT);
        $consumption = filter_var($data['consumption'], FILTER_VALIDATE_FLOAT);
        $invoicesModel = new InvoicesModel();
        $operator = $this->get_operator();

        if (!$client_id || !$consumption || !$counter) {
            $this->jsonResult(false, "Por favor, preencha todos os campos corretamente !");
            return;
        }

        $client = (new Client())->find()->where("id = '$client_id'")->execute();

        if (!$client || $client->status == 2) {
            $this->jsonResult(false, "O cliente indicado está inactivo, tente novamente.");
            return;
        }

        if ($consumption <= $_SESSION['config']['base_volume']) {
            $amount = floatval($_SESSION['base_price']);
        } else {
            $amount = floatval($consumption * $_SESSION['config']['price_per_m3']);
        }

        switch ($_SESSION['config']['expiry_mode']) {
            case 1:
                $today = new DateTime("now");
                $expiry_date = $today->add(new DateInterval("P{$_SESSION['config']['expiry_date']}D"));
                $expiry_date = $expiry_date->format("d-m-Y");
                break;

            case 2:
                $today = new DateTime("now");
                $expiry_date = $today->add(new DateInterval("P1M{$_SESSION['config']['expiry_date']}D"));
                $expiry_date = $expiry_date->format("d-m-Y");
                break;
        }

        $invoice = [
            "client_id" => $client->id,
            "consumption" => $consumption,
            "counter" => $counter,
            "amount" => $amount,
            "debt" => $amount,
            "expiry_date" => $expiry_date,
            "date_added" => date('d-m-Y'),
            "processed_by" => $operator['id'],
            "status" => 1
        ];

        $save = $invoicesModel->save($invoice)->execute();

        if ($save) {

//            if ($_SESSION['config']['auto_sms'] == 0) {
//
//                $send_auto_sms = Messages::invoice($client, (object)$invoice);
//
//                if ($send_auto_sms) {
//                    (new InvoicesModel())->update(["warned" => 1])->where("id = '$save'")->execute();
//                    $this->jsonResult(true, "A factura para $client->name foi emitida com sucesso !");
//                    return;
//                }
//
//                $this->jsonResult('w',
//                    "Emitiu com sucesso a factura para o cliente $client->name mas, não foi possível enviar a SMS de aviso !");
//                return;
//            }

            $this->jsonResult(true, "A factura para o cliente $client->name foi emitida com sucesso.");
            return;
        }

        $this->jsonResult(false, "Erro ao emitir, por favor tente novamente !");
    }

    public function cancel($data): void
    {
        if (!AdminSession::has() && !WorkerSession::has()) {
            $this->router->redirect("home");
        }

        $invoice_id = filter_var($data["id"], FILTER_VALIDATE_INT);

        $update = (new InvoicesModel())->update(["status" => 3, "debt" => 0, "fine" => 0])
            ->where("id = '$invoice_id'")->execute();

        if ($update) {
            $this->jsonResult(true, "Cancelou a factura com sucesso");
            return;
        }

        $this->jsonResult(false, "Erro ao cancelar, verifique o número da fatura e tente novamente !");
    }

    public function reactivate($data): void
    {
        if (!AdminSession::has() && !WorkerSession::has()) {
            $this->router->redirect("home");
        }

        $invoice_id = filter_var($data["id"], FILTER_VALIDATE_INT);

        $invoice = (new Invoice())->find()->where("id = '$invoice_id'")->execute();

        $update = (new Invoice())->update(["status" => 1, "debt" => $invoice->amount + $invoice->fine])
            ->where("id = '$invoice_id'")->execute();

        if ($update) {
            $this->jsonResult(true, "Cancelou a factura com sucesso");
            return;
        }

        $this->jsonResult(false, "Erro ao cancelar, verifique o número da factura e tente novamente !");
    }

    public function clearFine($data): void
    {
        if (!AdminSession::has() && !WorkerSession::has()) {
            $this->router->redirect("home");
        }

        $invoice_id = filter_var($data["id"], FILTER_VALIDATE_INT);

        $invoiceModel = new Invoice();
        $invoice = $invoiceModel->find()->where("id = '{$invoice_id}'")->execute();

        if ($invoice && $invoice->status == 4) {

            $invoiceModel->update(["debt" => $invoice->amount, "status" => 5, "fine" => 0])
                ->where("id = '$invoice_id'")->execute();
            $this->jsonResult(true, "Retirou a dívida na factura com sucesso");
            return;
        }

        $this->jsonResult(false, "Erro ao retirar, verifique o numero da factura e tente novamente !");
    }

    public function delete($data): void
    {
        if (!AdminSession::has() && !WorkerSession::has()) {
            $this->router->redirect("home");
        }

        $invoice_id = filter_var($data["id"], FILTER_VALIDATE_INT);

        $delete = (new Invoice())->delete()
            ->where("id = '$invoice_id'")->execute();

        if ($delete) {
            $this->jsonResult(true, "Apagou a fatura com sucesso");
            return;
        }

        $this->jsonResult(false, "Erro ao apagar a fatura, tente novamente !");
    }

    public function print($data)
    {
        $invoice_id = filter_var($data["id"], FILTER_VALIDATE_INT);

        $invoice = (new Invoice())->find()->where("id = '$invoice_id'")->execute();
        $client = (new Client())->find()->where("id = '$invoice->client_id'")->execute();
        $accounts = (new Accounts())->find()->execute(null , true);
        $last_invoices = (new Invoice())->find()
            ->where("id != '$invoice_id' AND id < $invoice_id AND client_id = '$client->id'")
            ->limit(3)->order()->execute(null, true);
        $config = (new \App\Models\Config())->find()->execute();
        $client->debts = (new Invoice())->find('SUM(debt) debts')->where("client_id = '$client->id'")
            ->execute()->debts;

        if ($invoice && (AdminSession::has() || WorkerSession::has()) ||
            (UserSession::has() && UserSession::get()["id"] == $client->id)) {
            PrintPdf::invoice($invoice, $last_invoices, $client, $accounts, $config);
            exit;
        }

        $this->router->redirect("home");
    }

}