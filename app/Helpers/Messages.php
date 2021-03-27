<?php

namespace App\Helpers;

use App\Models\Client;
use DateInterval;
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
        $date = date_create_from_format("d/m/Y", $invoice->date_added);
        $date_exp = $date->add(new DateInterval("P1M"));

        $body =
            "Caro Cliente, a sua fatura de agua deste mes:\n" .
            "A pagar: " . number_format($invoice->debt, 2, ",", ".") . " MT\n" .
            "Consumido: " . $invoice->consumption . " m3\n" .
            "Data limite: " . $date_exp->format("10-m-Y") . "\n" .
            "Evite multas pagando antes da data, obrigado!";

        $result = self::factory($client->phone, $body);

        return $result->getOmnimessageId() == true;
    }

    public static function receipt(object $receipt, object $client): bool
    {
        $month = (int)mb_split("/", $receipt->date_added)[1] + 1;
        $body =
            "Caro cliente, o recibo de pagamento no valor de {$receipt->amount} " .
            "MT para a fatura {$receipt->invoice_id} de " . month($month) . " ja foi emitido. Obrigado !";

        $result = self::factory($client->phone, $body);

        return $result->getOmnimessageId() == true;
    }

    public static function expiry(object $client): bool
    {
        $body = "Caro cliente, faltam 5 dias para a data limite de pagamento da" .
            " fatura de agua, pague antes da data e evite multas. Obrigado !";

        $result = self::factory($client->phone, $body);

        return $result->getOmnimessageId() == true;
    }

    public function send($data): void
    {
        $client = filter_var($data['client'], FILTER_VALIDATE_INT);
        $body = filter_var($data['body'], FILTER_SANITIZE_STRING);

        if (!$client || !$body || strlen($body) > 500) {
            echo ajax("msg", ["type" => "alert-danger", "msg" => "[!] Por favor, preencha todos os campos corretamente."]);
            return;
        }

        if ($client == 0) {
            $clients = (new Client())->find()->execute();
            $not_sent = [];

            foreach ($clients as $c) {
                $send = self::factory($c->phone, $body);

                if ($send->getOmnimessageId()) {
                    continue;
                }

                $not_sent[] = $c->name;
            }

            echo ajax("msg", ["type" => "alert-warning", "msg" => "Não foi possível enviar a SMS para o(s) cliente(s) " . implode(",", $not_sent)]);

        } else {

            $client = (new Client())->find("phone,name")->where("phone = '{$client}'")->execute();

            $phone = trim($client->phone);
            $result = $this->factory($phone, $body);

            if ($result->getOmnimessageId()) {
                echo ajax("msg", ["type" => "alert-danger", "msg" => "Enviou com sucesso a mensagem para {$client->name}"]);
                return;
            }

            echo ajax("msg", ["type" => "alert-danger", "msg" => "Não foi possível enviar a SMS para o cliente {$client->name}"]);
        }

    }

    public static function get_balance(): int
    {
        $cURLConnection = curl_init();

        curl_setopt($cURLConnection, CURLOPT_URL, "https://api2.messente.com/get_balance/?username=" . SMS_API["apiKey"] . "&password=" . SMS_API["apiSecret"]);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        $credits = curl_exec($cURLConnection);

        return mb_split(" ", $credits)[1] / 0.027;
    }

    private static function factory(string $phone, string $body)
    {
        $config = Configuration::getDefaultConfiguration()
            ->setUsername(SMS_API["apiKey"])
            ->setPassword(SMS_API["apiSecret"]);

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