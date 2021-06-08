<?php

include "../../vendor/autoload.php";

use App\Models\Config;
use App\Models\Invoice;

$config = (new Config())->find()->execute();
$invoices = (new Invoice())->find()->execute();

if ($invoices) {
    $count = 0;
    $applied = [];

    foreach ($invoices as $invoice) {
        if ($invoice->status == 1) {
            $today = new DateTime("now");
            $date = date_create_from_format("d-m-Y", $invoice->date_added);
            $exp = $date->add(new DateInterval("P1M"));
            $exp = date_create_from_format("d-m-Y",$exp->format("10/m/Y"));

            /* If the day passed, apply the fine */
            if ($today > $exp) {
                $update = (new Invoice())->update(
                    [
                        "status" => '4',
                        "fine" => "fine + $config->fine",
                        "debt" => "debt + $config->fine"
                    ]
                )->where("id = '$invoice->id'")->execute();

                if ($update) {
                    $applied[] = $invoice->id;
                    $count++;
                }
            }
        }
    }

    print "Terminated, applied on $count invoices (". implode(",", $applied) .")";
    exit;

} else {
    print "Terminated, didnt apply";
    exit;
}