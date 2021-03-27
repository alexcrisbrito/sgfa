<?php

include "../../vendor/autoload.php";

use App\Models\Invoice;

$model = new Invoice();

$facturas = $model->find();

if ($facturas) {

    $count = 0;
    $applied = [];

    foreach ($facturas as $factura) {
        if ($factura->Estado == 1) {

            $today = new DateTime("now");
            $date = date_create_from_format("d/m/Y", $factura->Date);
            $exp = $date->add(new DateInterval("P1M"));
            $exp = date_create_from_format("d/m/Y",$exp->format("10/m/Y"));

            /* If the day passed, apply the fine */
            if ($today > $exp) {
                $multa = $model->update(["Estado" => '4',"Multa" => "Multa + ".BUSINESS_MODEL["multa"],"Divida" => "Divida +".BUSINESS_MODEL["multa"]], $factura->ID);
                if ($multa) {
                    $applied[] = $factura->ID;
                    $count++;
                }

            }
        }
    }

    print "Terminated, applied on {$count} invoices -> [ ".implode(",",$applied)." ]";
    exit;
}else{
    print "Terminated, didnt apply";
    exit;
}