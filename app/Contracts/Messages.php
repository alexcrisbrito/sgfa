<?php

namespace App\Contracts;


use Exception;
use Messente\Api\Api\OmnimessageApi;
use Messente\Api\Model\Omnimessage;
use Messente\Api\Configuration;
use Messente\Api\Model\SMS;
use GuzzleHttp\Client as GuzzleClient;

class Messages
{
    public static function invoice(object $client, object $invoice): bool
    {
        $body =
            "Caro Cliente, a sua fatura de agua deste mes:\n" .
            "A pagar:". number_format($invoice->debt, 2, ",", ".") ." MT\n" .
            "Leitura:". $invoice->counter ." m3\n" .
            "Data limite:". $invoice->expiry_date ."\n" .
            "Evite multas pagando antes da data,obrigado!";

        $result = self::factory($client->phone, $body);

        return $result->getOmnimessageId() == true;
    }

    public static function receipt(object $receipt, object $client): bool
    {
        $month = (int)mb_split("-", $receipt->date_added)[1] + 1;
        $body =
            "Caro cliente, o recibo de pagamento no valor de $receipt->amount " .
            "MT para a fatura $receipt->invoice_id de ". month($month) ." ja foi emitido. Obrigado!";

        $result = self::factory($client->phone, $body);

        return $result->getOmnimessageId() == true;
    }

    public static function expiry(object $client): bool
    {
        $body = "Caro cliente, faltam 5 dias para a data limite de pagamento da" .
            " factura de agua, pague antes da data e evite multas. Obrigado!";

        $result = self::factory($client->phone, $body);

        return $result->getOmnimessageId() == true;
    }


    public static function get_balance(): int
    {
        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, "https://api2.messente.com/get_balance/?username=".
            SMS_API["apiKey"] ."&password=". SMS_API["apiSecret"]);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        $credits = curl_exec($cURLConnection);

        return mb_split(" ", $credits)[1] / 0.027;
    }

    public static function factory(string $phone, string $body)
    {
        $config = Configuration::getDefaultConfiguration()
            ->setUsername($_ENV['SMS_API_KEY'])
            ->setPassword($_ENV['SMS_API_SECRET']);

        $apiInstance = new OmnimessageApi(new GuzzleClient(), $config);
        $omnimessage = new Omnimessage(['to' => '+258' . trim($phone),]);
        $omnimessage->setMessages([new SMS(['text' => $body])]);

        try {
            return $apiInstance->sendOmnimessage($omnimessage);
        } catch (Exception $e) {
            return false;
        }
    }
}