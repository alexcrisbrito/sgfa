<?php


namespace App\Contracts;

use Dompdf\Dompdf;
use Dompdf\Options;
use League\Plates\Engine;


class PrintPdf
{
    private static function render(string $template, array $replacements) :string
    {
        $engine = new Engine(dirname(__DIR__, 1) . "/Views/docs", 'php');

        return $engine->render($template, $replacements);
    }

    private static function print(string $doc)
    {
        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($options);
        $dompdf->loadhtml($doc);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $dompdf->stream();
    }

    public static function receipt(object $receipt, object $client, object $invoice)
    {
        $doc = self::render("receipt", compact("receipt", "client", "invoice"));
        echo $doc;
//        self::print($doc);
    }

    public static function invoice(object $invoice,array $last_invoices, object $client, array $payment_methods, $config)
    {
        $doc = self::render("invoice", compact("invoice", "config","last_invoices", "client", "payment_methods"));
        echo $doc;
//        self::print($doc);
    }

}