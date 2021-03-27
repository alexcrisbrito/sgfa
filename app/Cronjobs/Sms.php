<?php

include "../../vendor/autoload.php";

use App\Controllers\Messages;
use App\Models\Client;
use App\Models\Invoice;

$com = new Messages();
$model = new Invoice();
$invoices = $model->find("*", "Date LIKE '%/" . date("m/Y") . "' AND Warned = '1' AND Estado = '1' OR Estado = '4'");

if ($invoices) {

    $count = 0;

    foreach ($invoices as $invoice) {

        $date = date_create_from_format("d/m/Y", $invoice->Date);
        $exp = date_create_from_format("d/m/Y",$date->add(new DateInterval("P1M"))->format("10/m/Y"));

        /* 5 days to invoice expiration message */
        if ($exp->diff(new DateTime("now"))->d >= 5) {

            $cliente = (new Client())->findById("Celular", $invoice->Cliente);
            $sms = $com->atraso($cliente);

            if ($sms) {
                $model->update(["Warned" => '2'], $invoice->ID);
                $count++;
            }

        }
    }

    die("Terminated, sent {$count} messages");

} else {
    die("Terminated, didn't send any messages");
}