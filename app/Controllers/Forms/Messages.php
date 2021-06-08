<?php


namespace App\Controllers\Forms;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Controllers\BaseController;
use App\Models\Client;

class Messages extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function send($data): void
    {
        $client = filter_var($data['client'], FILTER_VALIDATE_INT);
        $body = filter_var($data['body'], FILTER_SANITIZE_STRING);

        if (empty($client) || !$body || strlen($body) > 500) {
            $this->jsonResult(false, "Por favor, preencha todos os campos corretamente.");
            return;
        }

        if (!$client) {

            $clients = (new Client())->find()->execute();
            $not_sent = [];

            foreach ($clients as $client) {
                $send = \App\Contracts\Messages::factory($client->phone, $body);

                if ($send->getOmnimessageId()) {
                    continue;
                }

                $not_sent[] = $client->name;
            }

            $this->jsonResult(true, "Mensagens enviadas, mas não foi possível enviar a SMS para o(s) cliente(s) ". implode(",", $not_sent));
            return;
        }

        $client = (new Client())->find("phone,name")->where("phone = '$client'")->execute();

        $phone = trim($client->phone);
        $result = \App\Contracts\Messages::factory($phone, $body);

        if ($result->getOmnimessageId()) {
            $this->jsonResult(true, "Enviou com sucesso a mensagem para $client->name");
            return;
        }

        $this->jsonResult(false, "Não foi possível enviar a SMS para o cliente $client->name");
    }

    public function request($data) :void
    {
        $amount_sms = filter_var($data['amount_sms'], FILTER_VALIDATE_INT) ?? 100;

        if (!$amount_sms || $amount_sms < 100 || $amount_sms > 20000) {
            $this->jsonResult(false, "Por favor, preencha todos os campos corretamente.");
            return;
        }

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'notify@nextgenit-mz.com';
            $mail->Password   = $_ENV['NOTIFY_SECRET'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 465;

            $mail->setFrom('auto.notify@nextgenit-mz.com', 'SSGFA Notifier');
            $mail->addAddress('info@nextgenit-mz.com');

            $mail->isHTML(true);
            $mail->Subject = 'API Credits Request';
            $mail->Body    = $_ENV['LICENSED_TO'] . " is requesting ".$amount_sms." SMS API credits. 
            <br> Sent at ".date("d-m-Y H:i:s");

            $mail->send();
            $this->jsonResult(true, "Solicitação de compra de créditos enviada com sucesso,
             por favor aguarde por uma resposta.");

        } catch (Exception $e) {
            $this->jsonResult(false);
        }

    }

}