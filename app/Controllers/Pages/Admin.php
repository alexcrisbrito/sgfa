<?php

namespace App\Controllers\Pages;

use App\Helpers\AdminSession;
use PDO;
use stdClass;
use App\Contracts\Messages;
use App\Controllers\BaseController;
use App\Models\{Config, Login, Receipt, Invoice, Client, Expense};


class Admin extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);

        if (!AdminSession::has()) {
            $this->router->redirect("auth.logout");
        }
    }

    public function home(): void
    {
        $stats = new stdClass();
        $stats->profits = 0;
        $stats->total_profit = 0;
        $stats->total_consumption = 0;
        $stats->expenses = 0;
        $stats->ChartArea = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $stats->ChartPie = [0, 0, month((int)date('m', strtotime("-1 month"))), month()];

        $receipts = (new Receipt())->find()->execute(null, true);
        $invoices = (new Invoice())->find()->execute(null, true);
        $expenses = (new Expense())->find()->execute(null, true);
        $stats->clients = count((new Client())->find("id")->execute(PDO::FETCH_ASSOC, true));


        if ($receipts) {
            foreach ($receipts as $receipt) {
                /* Fill array of profits for each month and sum the profit of current year */
                if (preg_match("/-" . date("Y") . "/", $receipt->date_added)) {
                    $str = explode("-", $receipt->date_added);
                    $stats->ChartArea[intval($str[1]) - 1] += $receipt->amount;
                    $stats->total_profit += $receipt->amount;
                }
            }

            $stats->profits = $stats->ChartArea[(int)date('m') - 1];
        }

        if ($invoices) {
            foreach ($invoices as $invoice) {
                /* Consumo do mes passado */
                if (preg_match("/-" . date('m', strtotime("-1 month")) . "-" . date('Y') . "/", $invoice->date_added)) {
                    $stats->ChartPie[0] += $invoice->consumption;
                }
                /* Consumo do mes atual */
                if (preg_match("/-" . date('m') . "-" . date("Y") . "/", $invoice->date_added)) {
                    $stats->ChartPie[1] += $invoice->consumption;
                    $stats->total_consumption += $invoice->consumption;
                }
            }
        }

        if ($expenses) {
            foreach ($expenses as $expense) {
                /* Expenses of the month */
                if (preg_match("/\/" . date('m') . "\/" . date("Y") . "/", $expense->date_added)) {
                    $stats->expenses += $expense->amount;
                    $stats->profits -= $expense->amount;
                }

                /* Desconto no lucro liquido total anual */
                if (preg_match("/\/..\/" . date("Y/"), $expense->date_added)) {
                    $stats->total_profit -= $expense->amount;
                }
            }
        }

        echo $this->view->render("admin::home", [
            "stats" => $stats
        ]);
    }

    public function invoices(): void
    {
        $invoices = (new Invoice())->find()->order()->execute(PDO::FETCH_ASSOC, true);
        $clients = (new Client())->find("id,name,surname,counter_initial")
            ->execute(PDO::FETCH_ASSOC, true);

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

        foreach ($clients as $client) {
            $key = array_search($client['id'], array_keys($previous_readings));
            if ($key === false) $previous_readings[$client['id']] = floatval($client['counter_initial']);
        }

        echo $this->view->render("admin::invoices", [
            "data" => $invoices,
            "clients" => $clients,
            "previous_counter_readings" => json_encode($previous_readings)
        ]);
    }

    public function receipts(): void
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

        echo $this->view->render("admin::receipts", [
            "data" => $receipts,
            "invoices" => $invoices
        ]);
    }

    public function clients(): void
    {
        $clients = (new Client())->find()->execute(PDO::FETCH_ASSOC, true);
        $login = (new Login())->find("id,username,client_id")->execute(PDO::FETCH_ASSOC, true);
        $clients_id_haystack = array_column($login, "client_id");

        for ($i = 0; $i < count($clients); $i++) {
            $key = array_search($clients[$i]['id'], $clients_id_haystack);
            $clients[$i]['credentials'] = $login[$key];
        }

        echo $this->view->render("admin::clients", [
            "clients" => $clients ?: [],
        ]);
    }

    public function financial(): void
    {
        $stats = new stdClass();
        $stats->chartArea = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $stats->chartPie = [1, 1, 1, "N/A", "N/A", "N/A"];
        $stats->profits = 0;
        $stats->profitsTotal = 0;
        $stats->expenses = 0;

        $stats->chartPieValues = array_column($_SESSION['accounts'], 'balance');
        $stats->chartPieLabels = "'". implode("','", array_column($_SESSION['accounts'], 'short_name')) ."'";

        $receipts = (new Receipt())->find()->like('date_added', date("-Y"), 'end')->order()
            ->execute(null, true);
        $expenses = (new Expense())->find()->like('date_added', date("-Y"), 'end')->order()
            ->execute(PDO::FETCH_ASSOC, true);

        if ($receipts) {
            foreach ($receipts as $receipt) {
                if (preg_match("/..-" . date('m') . "-../", $receipt->date_added)) {
                    $stats->profits += $receipt->amount;
                }

                $str = explode("-", $receipt->date_added);
                $stats->chartArea[(int)$str[1] - 1] += $receipt->amount;
            }
        }

        if ($expenses) {
            for ($i = 0; $i < count($expenses); $i++) {
                if (preg_match("/..-" . date('m') . "-../", $expenses[$i]['date_added'])) {
                    $str = explode("-", $expenses[$i]['date_added']);
                    $stats->chartArea[(int)$str[1] - 1] -= $expenses[$i]['amount'];
                    $stats->profits -= $expenses[$i]['amount'];
                    $stats->expenses += $expenses[$i]['amount'];
                }

                $expenses[$i]['paid_via'] = $_SESSION['accounts'][(int)$expenses[$i]['account_id'] - 1]['name'];
            }
        }

        echo $this->view->render("admin::financial", [
            "expenses" => $expenses,
            "receipts" => $receipts,
            "stats" => $stats,
            "payment_methods" => $_SESSION['accounts']
        ]);
    }

    public function messages(): void
    {
        $clients = (new Client())->find()->execute(null, true);
        echo $this->view->render("admin::messages", [
            "clients" => $clients,
            "credits" => Messages::get_balance() ?? 0
        ]);
    }

    public function colab(): void
    {
        echo $this->view->render("admin::collab");
    }

    /* Next Update */
    public function consumption(): void
    {
        echo $this->view->render("admin::consumo");
    }

    public function config(): void
    {
        $config = (new Config())->find()->execute();
        $admins = (new \App\Models\Admin())->find()->order()->where("id != '" . AdminSession::get()['id'] . "'")
            ->execute(PDO::FETCH_ASSOC, true);
        $credentials = (new Login())->find("id,admin_id,username")
            ->execute(PDO::FETCH_ASSOC, true);

        $admins_id_haystack = array_column($credentials, "admin_id");

        for ($i = 0; $i < count($admins); $i++) {
            $key = array_search($admins[$i]['id'], $admins_id_haystack);
            $admins[$i]['credentials'] = $credentials[$key];
        }

        $you = AdminSession::get();
        $you['credentials'] = (new Login())->find("role,username")
            ->where("admin_id = '" . AdminSession::get()['id'] . "'")->execute(PDO::FETCH_ASSOC);

        echo $this->view->render("admin::config", [
            "config" => (array)$config,
            "admins" => $admins,
            "you" => $you
        ]);

    }

}