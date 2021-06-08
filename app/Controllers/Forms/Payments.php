<?php


namespace App\Controllers\Forms;


use abdulmueid\mpesa\Transaction;
use App\Controllers\BaseController;
use App\Helpers\AdminSession;
use App\Helpers\WorkerSession;
use App\Models\Invoice;
use App\Models\Transactions;

class Payments extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function pay($data) :void
    {
        $invoice_id = filter_var($data['invoice_id'], FILTER_VALIDATE_INT);
        $amount = filter_var($data['amount'], FILTER_VALIDATE_FLOAT);
        $phone = filter_var($data['phone'], FILTER_SANITIZE_STRING);

        if (!$invoice_id || !$amount || !$phone || $amount < 100.00) {
            $this->jsonResult(false, "Por favor, preencha todos os campos correctamente !");
            return;
        }

        $invoice = (new Invoice())->find()->where("id = '$invoice_id'")->execute();
        if ($invoice) {

            if ($amount > $invoice->debt) {
                $this->jsonResult(false, "O valor a pagar não pode ser maior que o valor em dívida");
                return;
            }

            $config = new \abdulmueid\mpesa\Config($_ENV['MPESA_PUB_KEY'], $_ENV['MPESA_API_HOST'],
                $_ENV['MPESA_API_KEY'], $_ENV['MPESA_ORIGIN'], $_ENV['MPESA_SERVICE_PROVIDER_CODE'], "", "");

            $transaction = new Transaction($config);
            $token = bin2hex(random_bytes(8))."FT000".$invoice_id;

            try {
                $c2b = $transaction->c2b($amount, $phone, "PAGT_FACTURAS", $token);
            }catch (\Exception $e) {
                $this->jsonResult(false, "Forneça um número vodacom válido !");
                return;
            }

            switch($c2b->getCode())
            {
                case "INS-0":
                    $this->save_transaction($token, $amount, $invoice_id, $c2b->getTransactionID());
                    $paid_via = 1;
                    (new Receipts($this->router))->add(compact("invoice_id", "amount", "paid_via"));
                    break;

                case "INS-5":
                    $this->jsonResult(false,"Cancelou a transação, por favor tente novamente !" );
                    break;

                case "INS-6":
                    $this->jsonResult(false,"Erro ao processar a transação, por favor tente novamente !");
                    break;

                case "INS-9":
                    $this->jsonResult(false,"O tempo para aceitar a transação acabou, tente novamente !");
                    break;

                case "INS-2006":
                    $this->jsonResult(false,"Transação cancelada por falta de saldo !");
                    break;

                case "INS-2051":
                    $this->jsonResult(false,"O número usado não é válido, por favor tente novamente !");
                    break;

                default:
                    $this->jsonResult(false,"Erro ao processar a transação, tente novamente !");
                    break;
            }

        }

    }

    public function save_transaction($token, $amount, $invoice_id, $external_token)
    {
        $save = (new Transactions())->save(compact("token", "amount", "invoice_id", "external_token"))
            ->execute();

        return $save == true;
    }
}