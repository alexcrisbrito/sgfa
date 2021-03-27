<?php

namespace App\Controllers\Forms;

use App\Controllers\Pages\Worker;
use App\Helpers\WorkerSession;
use App\Models\Client;
use App\Models\Client_Login;
use App\Models\Login;

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

        if (!$name || !$surname || !$phone || !$address) {
            echo ajax("msg", ["type" => "alert-danger", "msg" => "[!] Por favor, preencha todos os campos corretamente."]);
            return;
        }

        $save = (new Client())
            ->save(["name" => $name, "phone" => $phone, "address" => $address, "date_added" => date("d/m/Y")])
            ->execute();

        if ($save) {

            $client = (new Client())->find()->where("phone = '{$phone}'")->execute();
            $generated = [
                "username" => rand(000000, 999999),
                "password" => "sgfa123",
            ];

            $client_login = (new Client_Login())->save(
                [
                    "client_id" => $client->id,
                    "password" => password_hash($generated["password"], PASSWORD_DEFAULT)
                ]
            )->execute();

            (new Login())->save(
                [
                    "username" => $generated['username'],
                    "role" => 2,
                    "sub_id" => $client_login
                ]
            )->execute();

            echo ajax("msg", ["type" => "alert-success", "msg" => "O cliente foi adicionado com sucesso!<br> 
            O username para o login é: " . $generated["username"] . " e a senha: " . $generated["password"]]);
            return;
        }

        echo ajax("msg", ["type" => "alert-danger", "msg" => "Erro ao adicionar cliente, tente novamente !"]);
    }

    /* METHOD NOT USED */
    public function get($data)
    {
        $client_id = filter_var($data["client_id"], FILTER_VALIDATE_INT);

        $client = (new Client())->find()->where("id = '{$client_id}'")->execute();

        if ($client) {

            echo $this->view->render("other::edit_client", [
                "template" => WorkerSession::has() ? "worker::_template_" : "admin::_template_",
                "client" => $client
            ]);
            return;
        }

        $this->router->redirect("home");
    }

    public function update($data)
    {
        $client_id = filter_var($data["client_id"], FILTER_VALIDATE_INT);
        $name = filter_var($data["name"], FILTER_SANITIZE_STRING);
        $surname = filter_var($data["surname"], FILTER_SANITIZE_STRING);
        $phone = filter_var($data["phone"], FILTER_VALIDATE_INT);
        $address = filter_var($data["address"], FILTER_SANITIZE_STRING);

        if (!$client_id || !$name || !$surname || !$phone || !$address) {
            echo ajax("msg", ["type" => "alert-danger", "msg" => "Por favor, preencha todos os campos corretamente."]);
            return;
        }

        $update = (new Client())->update(compact("client_id", "surname","name", "phone", "address"));

        if ($update) {
            echo ajax("msg", ["type" => "alert-success", "msg" => "Atualizou o dados do cliente com sucesso !"]);
            return;
        }

        echo ajax("msg", ["type" => "alert-danger", "msg" => "[!] Erro ao atualizar, tente novamente."]);
    }

    public function status($data): void
    {
        $client_id = filter_var($data["client_id"], FILTER_VALIDATE_INT);
        $action = filter_var($data["action"], FILTER_VALIDATE_INT);

        if (!$client_id or !$action) {
            echo ajax("msg", ["type" => "error", "msg" => "[!] Por favor, preencha todos os campos corretamente."]);
            return;
        }

        $update = (new Client())->update(["status" => $action])
            ->where("id = '{$client_id}'")->execute();

        if ($update) {
            echo ajax("msg", ["type" => "success", "msg" => "Atualizou o estado do cliente com sucesso !"]);
            return;
        }

        echo ajax("msg", ["type" => "error", "msg" => "[!] O cliente selecionado não foi encontrado."]);
    }


}