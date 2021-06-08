<?php


namespace App\Controllers\Forms;

use App\Controllers\BaseController;

class Expenses extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function add($data): void
    {
        $name = filter_var($data["Nome"], FILTER_SANITIZE_STRING);
        $amount = filter_var($data["Valor"], FILTER_VALIDATE_FLOAT);

        if (!$name || !$amount) {
            echo ajax("msg", ["type" => "alert-danger", "msg" => "[!] Por favor, preencha todos os campos corretamente."]);
            return;
        }

        $save = (new \App\Models\Expense())->save(["name" => $name, "amount" => $amount, "date_added" => date("d/m/Y")])
            ->execute();
        if (!$save) {

            echo ajax("msg", ["type" => "alert-danger", "msg" => "[!] Erro ao registrar a despesa, tente novamente."]);
            return;
        }

        echo ajax("msg", ["type" => "alert-success", "msg" => "Despesa registada com sucesso !"]);
    }

    public function delete($data): void
    {
        $expense_id = filter_var($data["expense_id"], FILTER_VALIDATE_INT);

        $delete = (new \App\Models\Expense())->delete()->where("id = '{$expense_id}'")->execute();
        if (!$delete) {
            echo ajax("msg", ["type" => "error", "msg" => "[!] Erro ao apagar a despesa, tente novamente."]);
            return;
        }

        echo ajax("msg", ["type" => "success", "msg" => "Despesa apagada com sucesso !"]);
    }
}