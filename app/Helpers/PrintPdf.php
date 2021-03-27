<?php


namespace App\Helpers;

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

    private static function print(string $doc, string $name)
    {
        $options = new Options();
        $options->setIsHtml5ParserEnabled();
        $options->setIsRemoteEnabled();

        $dompdf = new Dompdf($options);
        $dompdf->loadhtml($doc);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $dompdf->stream($name);
    }

    public static function receipt(object $receipt, object $client)
    {
        $doc = self::render("receipt", compact("receipt", "client"));
        self::print($doc, "Recibo No {$receipt->id}");
    }

    public static function invoice(object $invoice, object $client)
    {
        $doc = self::render("invoice", compact("invoice", "client"));
        self::print($doc, "Fatura No {$invoice->id}");
    }

}