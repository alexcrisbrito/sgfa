<?php


namespace App\Controllers\Pages;


use App\Controllers\BaseController;
use App\Helpers\SessionManager;
use App\Helpers\UserSession;
use App\Models\Accounts;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Receipt;
use PDO;
use stdClass;

class User extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);

        if (!UserSession::has()) {
            $this->router->redirect("auth.logout");
        }
    }

    public function home(): void
    {
        $stats = new stdClass();
        $stats->monthConsumption = 0;
        $stats->totalMoney = 0;
        $stats->debts = 0;
        $stats->chart = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $stats->counter = 0;

        $client = (new Client())->find()->where("id = '". UserSession::get()['id'] ."'")->execute();
        $invoices = (new Invoice())->find()->where("client_id = '". UserSession::get()['id'] ."'")
            ->order()->like("date_added", date('-Y'), "end")
            ->execute(PDO::FETCH_ASSOC, true);

        if (!empty($invoices)) {

            $stats->monthConsumption = $invoices[0]['consumption'];
            $stats->counter = $invoices[0]['counter'];

            foreach ($invoices as $invoice) {

                $str = explode("-", $invoice['date_added']);
                $stats->chart[intval($str[1]) - 1] += $invoice['consumption'];
                $stats->totalMoney += $invoice['amount'] + $invoice['fine'];

                if ($invoice['status'] == 1) {
                    $stats->debts += $invoice['debt'];
                }
            }

        }

        echo $this->view->render("user::home", [
            "stats" => $stats,
            "client" => $client
        ]);
        exit;
    }

    public function invoices(): void
    {
        $client = (new Client())->find()->where("id = '". UserSession::get()['id'] ."'")->execute();
        $invoices = (new Invoice())->find()->where("client_id = '". UserSession::get()['id'] ."'")
            ->order()->execute(null, true);

        echo $this->view->render("user::invoices", [
            "invoices" => $invoices,
            "client" => $client
        ]);
    }

    public function receipts(): void
    {
        $client = UserSession::get();
        $receipts = (new Receipt())->find()->order()
            ->where("client_id = '". $client['id'] ."'")->execute(PDO::FETCH_ASSOC, true);

        $accounts = (new Accounts())->find("name")->execute(PDO::FETCH_ASSOC, true);

        for ($i = 0; $i< count($receipts); $i++) {
            $receipts[$i]['paid_via'] = $accounts[$receipts[$i]['paid_via'] - 1]['name'];
        }

        echo $this->view->render("user::receipts", [
            "receipts" => $receipts,
            "client" => $client,
        ]);
    }

    public function config() :void
    {
        $client = UserSession::get();

        echo $this->view->render("user::config", [
            "client" => (object)$client
        ]);
    }

    public function pay() :void
    {
        $client = UserSession::get();
        $invoices = (new Invoice())->find()->where("client_id = '{$client['id']}' AND status IN(1,4)")
            ->execute(null, true);

        echo $this->view->render("user::pay", compact("client", "invoices"));
    }

}