<?php


namespace App\Controllers\Pages;


use App\Controllers\BaseController;
use App\Helpers\UserSession;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use PDO;

class User extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);

        if (!UserSession::has() || Session::isFirstLogin()) {
            $this->router->redirect("pub.login");
        }
    }

    public function home(): void
    {
        $stats = new stdClass();
        $stats->monthConsumption = 0;
        $stats->totalConsumption = 0;
        $stats->debts = 0;
        $stats->chart = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        $client = (new Client())->find()->where("id = '". UserSession::get()['id'] ."'")->execute();
        $invoices = (new Invoice())->find()->where("client_id = '". UserSession::get()['id'] ."'")
            ->order()->like("date_added", date('/Y'), "end")
            ->execute(PDO::FETCH_ASSOC, true);

        if ($invoices) {

            $stats->monthConsumption = $facturas[0]['consumption'];

            foreach ($invoices as $invoice) {

                $str = explode("/", $invoice['date_added']);
                $stats->chart[intval($str[1]) - 1] += $invoice['consumption'];
                $stats->totalConsumption += $invoice['consumption'];

                if ($invoice['status'] == 1) {
                    $stats['debts'] += $invoice['amount'];
                }
            }
        }

        echo $this->view->render("user::home", [
            "stats" => $stats,
            "client" => $client
        ]);
    }

    public function invoices(): void
    {
        $client = (new Client())->find()->where("id = '". UserSession::get()['id'] ."'")->execute();
        $invoices = (new Invoice())->find()->where("client_id = '". UserSession::get()['id'] ."'")->order()->execute();

        echo $this->view->render("user::invoice", [
            "invoices" => $invoices,
            "client" => $client
        ]);
    }

    public function receipts(): void
    {
        $client = (new Client())->find()->where("id = '". UserSession::get()['id'] ."'")->execute();
        $receipt = (new Receipt())->find()->where("id = '". UserSession::get()['id'] ."'")->execute();

        echo $this->view->render("user::receipts", [
            "receipts" => $receipt,
            "client" => $client,
        ]);
    }


}