<?php

namespace App\Controllers\Pages;

use App\Controllers\BaseController;
use PDO;
use App\Models\{
    Receipt,
    Invoice,
    Client,
    Expenses
};
use stdClass;

class Admin extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);

        if (!isset($_SESSION["ID"], $_SESSION["TIPO"]) or $_SESSION["TIPO"] != 2) {
            $this->router->redirect("pub.login");
        }
    }

    //ADMINISTRATION PAGES
    public function home(): void
    {
        $stats = new stdClass();
        $stats->profits = 0;
        $stats->total_profit = 0;
        $stats->LitrosGastos = 0;
        $stats->expenses = 0;
        $stats->ChartArea = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $stats->chartPie = [0.1, 0.1, month((int)date('m', strtotime("-1 month"))), month()];

        $recibos = (new Receipt())->find()->execute();
        $facturas = (new Invoice())->find()->execute();
        $despesas = (new Expenses())->find()->execute();

        if ($recibos) {
            foreach ($recibos as $recibo) {
                /* Fill array of profits for each month and sum the profit of current year */
                if (preg_match("/\/..\/" . date("Y") . "/", $recibo->Date)) {
                    $str = explode("/", $recibo->Date);
                    $stats->ChartArea[intval($str[1]) - 1] += $recibo->Valor;
                    $stats->total_profit += $recibo->Valor;
                }
            }

            $stats->profits = $stats->ChartArea[(int)date('m') - 1];
        }

        if ($facturas) {
            foreach ($facturas as $factura) {
                /* Consumo do mes passado */
                if (preg_match("/\/" . date('m', strtotime("-1 month")) . "\/../", $factura->Date)) {
                    $stats->chartPie[0] += $factura->Consumo;
                }
                /* Consumo do mes atual */
                if (preg_match("/\/" . date('m') . "\/" . date("Y") . "/", $factura->Date)) {
                    $stats->chartPie[1] += $factura->Consumo;
                    $stats->LitrosGastos += $factura->Consumo;
                }
            }
        }

        if ($despesas) {
            foreach ($despesas as $despesa) {
                /* Expenses of the month */
                if (preg_match("/\/" . date('m') . "\/" . date("Y") . "/", $despesa->Date)) {
                    $stats->expenses += $despesa->Valor;
                    $stats->profits -= $despesa->Valor;
                }

                /* Desconto no lucro liquido total anual */
                if (preg_match("/\/..\/" . date("Y/"), $despesa->Date)) {
                    $stats->total_profit -= $despesa->Valor;
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
        $clients = (new Client())->find()->execute(PDO::FETCH_ASSOC, true);

        /* Transform the id into name just for convenience */
        $names = array_column($clients, "name");
        $surnames = array_column($clients, "surname");
        $ids = array_column($clients, "id");

        $client_names = array_merge_recursive($ids, $names, $surnames);

        for ($i = 0; $i < count($invoices); $i++) {
            $key = array_search($invoices[$i]['client_id'], $client_names);
            $invoices[$i]['client'] = $client_names[$key]['name'] . " " . $client_names[$key]['surname'];
        }

        echo $this->view->render("admin::invoices", [
            "dados" => $invoices,
            "clientes" => $clients
        ]);
    }

    public function receipts(): void
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

        echo $this->view->render("admin::receipts", [
            "receipts" => $receipts,
            "invoices" => $invoices
        ]);
    }

    public function clients(): void
    {
        $clients = (new Client())->find()->execute();

        echo $this->view->render("admin::clients", [
            "clients" => $clients,
        ]);
    }

    public function financial(): void
    {
        $stats = new stdClass();
        $stats->chartArea = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $stats->chartPie = [1, 1, 1, "N/A", "N/A", "N/A"];

        $recibos = (new Receipt())->find()->execute();
        $despesas = (new Expenses())->find()->order()->execute();

        if ($recibos) {

            foreach ($recibos as $recibo) {
                //Fill the array of profits of each month
                if (preg_match("/\/..\/" . date("Y") . "/", $recibo->date_added)) {
                    $str = explode("/", $recibo->date_added);
                    $stats->chartArea[(int)$str[1] - 1] += $recibo->amount;
                }
            }
        }

        if ($despesas) {
            $i = 0;
            foreach ($despesas as $despesa) {

                /* Desconto do lucro liquido */
                if (preg_match("/\/" . date('m') . "\/" . date("Y") . "/", $despesa->date_added)) {
                    $str = explode("/", $despesa->date_added);
                    $stats->chartArea[(int)$str[1] - 1] -= $despesa->amount;
                }

                /* Ultimas 3 despesas */
                if ($i < 3) {
                    $stats->chartPie[$i] = $despesa->amount;
                    $stats->chartPie[$i + 3] = $despesa->name;
                    $i++;
                }
            }

            $stats->mid_profit = array_sum($stats->chartArea) / count($stats->chartArea);
        }

        echo $this->view->render("admin::financial", [
            "dados" => $despesas,
            "recibos" => $recibos,
            "stats" => $stats
        ]);
    }

    public function messages(): void
    {
        $clients = (new Client())->find()->execute();
        echo $this->view->render("admin::messages", [
            "clients" => $clients,
            "credits" => Messages::get_balance()
        ]);
    }

    /* Next Update */
    public function colab(): void
    {
        echo $this->view->render("admin::colab");
    }

    /* Next Update */
    public function consumption(): void
    {
        echo $this->view->render("admin::consumo");
    }

    /* Next Update Feature */
    public function config(): void
    {
        echo $this->view->render("admin::conf");
    }

}