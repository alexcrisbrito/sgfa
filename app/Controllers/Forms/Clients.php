<?php

namespace App\Controllers\Forms;

use App\Helpers\AdminSession;
use App\Helpers\UserSession;
use App\Helpers\WorkerSession;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Login;
use App\Controllers\BaseController;
use App\Models\Receipt;


class Clients extends BaseController
{

    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function add(array $data): void
    {
        $name = filter_var($data["name"], FILTER_SANITIZE_STRING);
        $surname = filter_var($data["surname"], FILTER_SANITIZE_STRING);
        $phone = filter_var($data["phone"], FILTER_VALIDATE_INT);
        $address = filter_var($data["address"], FILTER_SANITIZE_STRING);
        $counter_number = filter_var($data['counter_number'], FILTER_SANITIZE_STRING);
        $counter_initial = filter_var($data['counter_initial'], FILTER_VALIDATE_FLOAT);

        if (!$name || !$surname || !$phone || !$address || empty($counter_number) || empty($counter_initial)) {
            $this->jsonResult(false, "Por favor, preencha todos os campos corretamente!");
            return;
        }

        $save = (new Client())
            ->save(compact("name", "surname", "address", "phone", "counter_initial", "counter_number"))
            ->execute();

        if ($save) {
            $generated = [
                "username" => strtolower($surname.$name).rand(10, 99),
                "password" => "sgfa".rand(1000, 9999),
            ];

            (new Login())->save(
                [
                    "client_id" => $save,
                    "username" => $generated['username'],
                    "role" => 2,
                    "password" => password_hash($generated['password'], PASSWORD_DEFAULT)
                ]
            )->execute();

            $this->jsonResult(true, "O novo cliente foi adicionado com sucesso!<br> 
            O username para o login é: " . $generated["username"] . " e a senha: " . $generated["password"]);
            return;
        }

        $this->jsonResult(false, "Erro ao adicionar cliente, tente novamente !");
    }

    public function update($data)
    {
        $id = filter_var($data["id"], FILTER_VALIDATE_INT);
        $name = filter_var($data["name"], FILTER_SANITIZE_STRING);
        $surname = filter_var($data["surname"], FILTER_SANITIZE_STRING);
        $phone = filter_var($data["phone"], FILTER_VALIDATE_INT);
        $address = filter_var($data["address"], FILTER_SANITIZE_STRING);

        if (!$id || !$name || !$surname || !$phone || !$address) {
            $this->jsonResult(false,"Por favor, preencha todos os campos corretamente.");
            return;
        }

        $update = (new Client())
            ->update(compact("surname","name", "phone", "address"))
            ->where("id = '$id'")->execute();

        if ($update) {
            $this->jsonResult(true, "Atualizou os dados do cliente com sucesso.");
            return;
        }

        $this->jsonResult(false, "Erro ou nenhum dado novo a atualizar, tente novamente !");
    }

    public function status($data): void
    {
        $client_id = filter_var($data["id"], FILTER_VALIDATE_INT);
        $switch = filter_var($data["switch"], FILTER_VALIDATE_INT);

        if (!$client_id || !in_array($switch, [0,1])) {
            $this->jsonResult(false, "Por favor, selecione um cliente !");
            return;
        }

        $update = (new Client())->update(["status" => (int)!$switch])
            ->where("id = '$client_id'")->execute();

        $update_login = (new Login())->update(["status" => (int)!$switch])
            ->where("client_id = '$client_id'")->execute();

        if ($update && $update_login) {
            $this->jsonResult(true);
            return;
        }

        $this->jsonResult(false, "Erro ao atualizar o estado do cliente, tente novamente !");
    }

    public function historic($data) :void
    {
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);

        if (WorkerSession::has() || AdminSession::has() || (UserSession::has() && UserSession::get()['id'] == $id)) {
            $client = (new Client())->find()->where("id = '$id'")->execute();
            $invoices = (new Invoice())->find()->where("client_id = '$id'")->order()->execute(null, true);
            $receipts = (new Receipt())->find()->where("client_id = '$id'")->order()->execute(null, true);

            echo $this->view->render("docs::client_report", compact("invoices", "client", "receipts"));
            return;
        }

        echo "<h1>Sem permissão para acessar a página</h1>";
    }

}