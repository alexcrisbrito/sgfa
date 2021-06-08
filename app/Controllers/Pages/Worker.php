<?php

namespace App\Controllers\Pages;


use App\Contracts\Messages;
use App\Helpers\UserSession;
use App\Helpers\WorkerSession;
use App\Models\Client;
use App\Models\Config;
use App\Models\Invoice;
use App\Models\Login;
use App\Models\Receipt;
use App\Controllers\BaseController;
use PDO;

class Worker extends BaseController
{

    public function __construct($router)
    {
        parent::__construct($router);

        if (!WorkerSession::has()) {
            $this->router->redirect("auth.logout");
        }

    }

    public function index()
    {
        $clients = (new Client())->find("COUNT(id) count")->execute();
        $invoices = (new Invoice())->find()->like("date_added", date('Y'), "end")
            ->execute(null, true);

        /* Sum of clients consumption for each month */
        $chartArea = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        /* Filling of client consumption to array */
        if ($invoices) {
            for ($i = 0; $i < count($invoices); $i++) {
                $str = explode("-", $invoices[$i]->date_added);
                $chartArea[intval($str[1]) - 1] += $invoices[$i]->consumption;
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

        $invoices = (new Invoice())->find()->order()
            ->execute(PDO::FETCH_ASSOC, true);
        $invoices_id_haystack = array_column($invoices, "id");

        $clients_id_haystack = array_column($invoices, "client_id");
        $clients = (new Client())->find("id,name,surname")
            ->in($clients_id_haystack)->execute(PDO::FETCH_ASSOC, true);

        /* Populate the clients */

        for ($i = 0; $i < count($receipts); $i++) {
            $key = array_search($receipts[$i]['invoice_id'], $invoices_id_haystack);
            $receipts[$i]['invoice'] = $invoices[$key];
            unset($receipts[$i]['invoice_id']);

            $key = array_search($receipts[$i]['client_id'], $clients_id_haystack);
            $receipts[$i]['client'] = $clients[$key];
            unset($receipts[$i]['client_id']);

            $receipts[$i]['paid_via'] = $_SESSION['accounts'][$receipts[$i]['paid_via'] - 1];
        }

        /* Populate the invoices */
        for ($i = 0; $i < count($invoices); $i++) {
            $key = array_search($invoices[$i]['client_id'], $clients_id_haystack);
            $invoices[$i]['client'] = $clients[$key];
            unset($invoices[$i]['client_id']);
        }

        echo $this->view->render("worker::receipts", [
            "data" => $receipts,
            "invoices" => $invoices
        ]);
    }

    public function invoices()
    {
        $invoices = (new Invoice())->find()->order()->execute(PDO::FETCH_ASSOC, true);
        $clients = (new Client())->find("id,name,surname")->execute(PDO::FETCH_ASSOC, true);

        $pool = array_column($clients, "id");

        for ($i = 0; $i < count($invoices); $i++) {
            $key = array_search($invoices[$i]['client_id'], $pool);
            $invoices[$i]['client'] = $clients[$key];
            unset($invoices[$i]['client_id']);
        }

        $readings = (new Invoice())->find("client_id, MAX(counter) counter")
            ->order("client_id", "ASC")->group_by("client_id")
            ->execute(PDO::FETCH_ASSOC, true);

        $previous_readings = [];
        foreach ($readings as $reading) {
            $previous_readings[$reading['client_id']] = floatval($reading['counter']);
        }

        echo $this->view->render("worker::invoices", [
            "data" => $invoices,
            "clients" => $clients,
            "previous_counter_readings" => json_encode($previous_readings)
        ]);
    }

    public function clients()
    {
        $clients = (new Client())->find()->execute(PDO::FETCH_ASSOC, true);
        $login = (new Login())->find("id,username,client_id")->execute(PDO::FETCH_ASSOC, true);
        $clients_id_haystack = array_column($login, "client_id");

        for ($i = 0; $i < count($clients); $i++) {
            $key = array_search($clients[$i]['id'], $clients_id_haystack);
            $clients[$i]['credentials'] = $login[$key];
        }

        echo $this->view->render("worker::clients", [
            "clients" => $clients ?: []
        ]);

    }

    public function messages()
    {
        $clients = (new Client())->find()->execute(null, true);
        echo $this->view->render("worker::messages", [
            "clients" => $clients,
            "credits" => Messages::get_balance() ?? 0
        ]);
    }

    public function config(): void
    {
        $config = (new Config())->find()->execute();
        $login = (new Login())->find()->where("admin_id = '".WorkerSession::get()['id']."'")->execute();
        $you = WorkerSession::get();
        $you['credentials'] = (array)$login;
        echo $this->view->render("worker::config", [
            "config" => (array)$config,
            "you" => $you
        ]);

    }


}