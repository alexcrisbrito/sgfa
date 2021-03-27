<?php

namespace App\Controllers\Pages;


use App\Helpers\UserSession;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Controllers\BaseController;
use PDO;

class Worker extends BaseController
{

    public function __construct($router)
    {
        parent::__construct($router);

        if (!UserSession::has() || UserSession::get()['role'] !== 3) {
            $this->router->redirect("pub.login");
        }

    }

    public function index()
    {
        $clients = (new Client())->find("COUNT(id) as count")->execute();
        $invoices = (new Invoice())->find()->like("date_added", date('/Y'), "end")
            ->execute();

        /* Sum of clients consumption for each month */
        $chartArea = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        /* Filling of client consumption to array */
        if ($invoices) {
            foreach ($invoices as $invoice) {
                $str = explode("/", $invoice->date_added);
                $chartArea[intval($str[1]) - 1] += $invoice->consumption;
            }
        }

        echo $this->view->render("worker::index", [
            "clients" => $clients,
            "chart" => $chartArea,
            "consumption" => array_sum(array_column($invoices, "consumption")) ?: 0
        ]);
    }

    public function receipts()
    {
        $receipts = (new Receipt())->find()->order()->execute(PDO::FETCH_ASSOC, true);

        $invoices_id_haystack = implode(",", array_column($receipts, "invoice_id"));
        $invoices = (new Invoice())->find("id,client_id")->like("date_added", date('/Y'), "end")
            ->order()->where("status IN(1,4,5) AND id IN({$invoices_id_haystack})")
            ->execute(PDO::FETCH_ASSOC, true);

        $clients_id_haystack = array_column($invoices, "client_id");
        $clients = (new Client())->find("name,surname")->in($clients_id_haystack, "id")->execute();

        for ($i = 0; $i < count($invoices); $i++) {
            $key = array_search($invoices[$i]['client_id'], $clients_id_haystack);
            $receipts[$i]['client'] = $clients[$key]['name'] . " " . $clients[$key]['surname'];
        }

        echo $this->view->render("worker::receipts", [
            "receipts" => $receipts,
            "invoices" => $invoices
        ]);
    }

    public function invoices()
    {
        $invoices = (new Invoice())->find()->order()
            ->execute(PDO::FETCH_ASSOC, true);
        $clients = (new Client())->find("id,name,surname")
            ->execute(PDO::FETCH_ASSOC, true);

        for ($i = 0; $i < count($invoices); $i++) {
            $key = array_search($invoices[$i]['client_id'], $clients);
            $invoices[$i]['client'] = $clients[$key]['name'] . " " . $clients[$key]['surname'];
        }

        echo $this->view->render("worker::invoices", [
            "invoices" => $invoices,
            "clients" => $clients
        ]);
    }

    public function clients()
    {
        $clients = (new Client())->find()->execute();
        echo $this->view->render("worker::clients", [
            "clients" => $clients
        ]);

    }

    public function messages()
    {
        $clients = (new Client())->find()->execute();
        echo $this->view->render("worker::messages", [
            "clients" => $clients,
            "credits" => Messages::get_balance()
        ]);
    }

}