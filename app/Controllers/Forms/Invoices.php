<?php

namespace App\Controllers\Forms;

use App\Helpers\AdminSession;
use App\Helpers\ConfigHelper;
use App\Helpers\Messages;
use App\Helpers\PrintPdf;
use App\Helpers\UserSession;
use App\Helpers\WorkerSession;
use App\Models\Client;
use App\Controllers\BaseController;
use App\Models\Invoice;
use App\Models\Invoice as InvoicesModel;


class Invoices extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);

        if (!AdminSession::has() || !WorkerSession::has()) {
            $this->router->redirect("home");
        }
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
        $client_id = filter_var($data["client_id"], FILTER_SANITIZE_STRING);
        $consumption = filter_var($data["consumption"], FILTER_VALIDATE_FLOAT);
        $invoicesModel = new InvoicesModel();
        $operator = $this->get_operator();

        if (!$client_id or !$consumption) {
            echo ajax("msg", ['type' => "alert-warning", 'msg' => "[!] Por favor, preencha todos os campos corretamente !"]);
            return;
        }

        $client = (new Client())->find()->where("id = '{$client_id}'")->execute();

        if (!$client || $client->status == 2) {
            echo ajax("msg", ["type" => "alert-danger", "msg" => "[!] O cliente informado não existe ou está inativo, tente novamente."]);
            return;
        }

        $amount = $consumption > BUSINESS_MODEL["baseVolume"] ?
            floatval($consumption) * BUSINESS_MODEL["Price"] :
            BUSINESS_MODEL["basePrice"];

        $invoice = [
            "client_id" => $client->ID,
            "consumption" => $consumption,
            "amount" => $amount,
            "debt" => $amount,
            "date_added" => date('d/m/Y'),
            "processed_by" => $operator['id']
        ];

        $save = $invoicesModel->save($invoice)->execute();

        if ($save) {

            if (ConfigHelper::get('send_auto_sms')) {

                $send_auto_sms = Messages::invoice($client, (object)$invoice);

                if ($send_auto_sms) {
                    (new InvoicesModel())->update(["warned" => 1])->where("id = '{$save}'")->execute();
                    echo ajax("msg", ["type" => "alert-success", "msg" => "Fatura para {$client->name} emitida com sucesso !"]);
                    return;
                }

                echo ajax("msg", ["type" => "alert-warning", "msg" => "(!) Emitiu com sucesso fatura para {$client->name} <br> erro ao enviar a SMS de aviso !"]);
                return;
            }
        }

        echo ajax("msg", ["type" => "alert-danger", "msg" => "[!] Erro ao emitir, por favor tente novamente."]);
    }

    public function get($data): void
    {
        $invoice_id = $data["invoice_id"];

        $invoice = (new InvoicesModel())->find()->where("id = '{$invoice_id}'")->execute();
        $client = (new Client())->find()->where("id = '{$invoice->client_id}'")->execute();

        if ($invoice && ((AdminSession::has() || WorkerSession::has()) || (UserSession::has() && UserSession::get()['id'] == $client->id))) {

            echo $this->view->render("docs::invoice", [
                "invoice" => $invoice,
                "client" => $client
            ]);

            return;
        }

        echo ajax("msg", ["type" => "alert-danger", "msg" => "[!] Sem registo da fatura indicada, tente novamente."]);
    }

    public function update($data): void
    {
        $invoice_id = filter_var($data["invoice_id"], FILTER_SANITIZE_STRING);
        $prop = filter_var($data["prop"], FILTER_SANITIZE_STRING);
        $data = filter_var($data["data"], FILTER_SANITIZE_STRING);

        if (!$invoice_id || !$prop || !$data) {
            echo ajax("msg", ["type" => "warning", "msg" => "[!] Por favor, preencha todos os campos corretamente."]);
            return;
        }

        $update = (new InvoicesModel())->update([$prop => $data])
            ->where("id = '{$invoice_id}'")->execute();

        if ($update) {
            echo ajax("msg", ["type" => "success", "msg" => "Atualizou a fatura com sucesso !"]);
            return;
        }

        echo ajax("msg", ["type" => "error", "msg" => "[!] Por favor, verifique o número da fatura e tente novamente."]);
    }

    public function cancel($data): void
    {
        $invoice_id = filter_var($data["invoice_id"], FILTER_VALIDATE_INT);

        $update = (new InvoicesModel())->update(["status" => 3, "debt" => 0, "fine" => 0])
            ->where("id = '{$invoice_id}'")->execute();

        if ($update) {

            echo ajax("msg", ["type" => "success", "msg" => "Cancelou a fatura com sucesso !"]);
            return;
        }

        echo ajax("msg", ["type" => "error", "msg" => "[!] Por favor, verifique o número da fatura e tente novamente !"]);
    }

    public function clearFine($data): void
    {
        $invoice_id = filter_var($data["invoice_id"], FILTER_VALIDATE_INT);

        $invoiceModel = new InvoicesModel();
        $invoice = $invoiceModel->find()->where("id = '{$invoice_id}'")->execute();

        if ($invoice && $invoice->status == 4) {

            $invoiceModel->update(["debt" => $invoice->amount, "status" => 5, "fine" => 0])
                ->where("id = '{$invoice_id}'")->execute();
            echo ajax("msg", ["type" => "success", "msg" => "[!] Cancelou a dívida da fatura com sucesso."]);
            return;
        }

        echo ajax("msg", ["type" => "error", "msg" => "[!] Por favor, verifique o número da fatura e tente novamente."]);
    }

    public function delete($data): void
    {
        $invoice_id = filter_var($data["id"], FILTER_VALIDATE_INT);

        $delete = (new InvoicesModel())->delete()
            ->where("id = '{$invoice_id}'")->execute();

        if ($delete) {
            echo ajax("msg", ["type" => "success", "msg" => "Apagou a fatura com sucesso !"]);
            return;
        }

        echo ajax("msg", ["type" => "danger", "msg" => "[!] Erro ao apagar a fatura, tente novamente."]);
    }

    public function print($data)
    {
        $invoice_id = filter_var($data["invoice_id"], FILTER_VALIDATE_INT);

        $invoice = (new Invoice())->find()->where("id = '{$invoice_id}'")->execute();
        $client = (new Client())->find()->where("id = '{$invoice->client_id}'")->execute();

        if ($receipt && UserSession::get()['id'] == 0 || UserSession::get()["id"] == $client->id) {
            PrintPdf::invoice($invoice, $client);
            exit;
        }

        $this->router->redirect("home");
    }

}