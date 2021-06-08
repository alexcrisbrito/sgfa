<?php


namespace App\Controllers\Forms;


use App\Controllers\BaseController;
use App\Models\Accounts;
use App\Models\Expense;

class Financial extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function expense_add($data) :void
    {
        $name = filter_var($data["name"], FILTER_SANITIZE_STRING);
        $amount = filter_var($data["amount"], FILTER_VALIDATE_FLOAT);
        $account_id = filter_var($data["account_id"], FILTER_VALIDATE_INT);
        $description = filter_var($data['description'], FILTER_SANITIZE_STRING) ?: "";

        if (!$name || !$amount || !$account_id || !$description) {
            $this->jsonResult(false, "Por favor, preencha todos os campos corretamente !");
            return;
        }

        $save = (new Expense())->save(compact("name", "amount", "account_id", "description"))->execute();
        $update = (new Accounts())->update(["balance" => (float)$_SESSION['accounts'][$account_id - 1]['balance'] - $amount])
            ->where("id = '$account_id'")->execute();

        if ($save && $update) {
            $_SESSION['accounts'][$account_id - 1]['balance'] -= $amount;
            $this->jsonResult(true, "Despesa registrada com sucesso !");
            return;
        }

        $this->jsonResult(false, "Erro ao registrar a despesa, tente novamente !");
    }


    public function account_add($data) :void
    {
        $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $short_name = filter_var($data['short_name'], FILTER_SANITIZE_STRING);
        $balance = filter_var($data['balance'], FILTER_VALIDATE_FLOAT);

        if (!$name || !$short_name) {
            $this->jsonResult(false, "Por favor, preencha todos os campos corretamente !");
            return;
        }

        $save = (new Accounts())->save(compact("name", "balance", "short_name"))->execute();
        if ($save) {
            $_SESSION['accounts'][] = [
                "id" => $save,
                "name" => $name,
                "balance" => $balance ?? 0.00,
                "short_name" => $short_name,
                "date_added" => date('d-m-Y')
            ];
            $this->jsonResult(true, "Conta criada com sucesso !");
            return;
        }

        $this->jsonResult(false, "Erro ao criar a conta, tente novamente !");
    }


}