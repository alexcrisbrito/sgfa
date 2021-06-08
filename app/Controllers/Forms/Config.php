<?php


namespace App\Controllers\Forms;


use App\Controllers\BaseController;
use App\Helpers\AdminSession;
use App\Helpers\SessionManager;
use App\Helpers\UserSession;
use App\Helpers\WorkerSession;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Login;
use Exception;

class Config extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function update_config($data): void
    {
        if (!AdminSession::has() || $_SESSION['config']['admin_id'] != AdminSession::get()['id']) {
            $this->jsonResult(false, "Sem permissões para realizar a acção !");
            return;
        }

        $expiry_mode = filter_var($data['expiry_mode'], FILTER_VALIDATE_INT);
        $expiry_date = filter_var($data['expiry_date'], FILTER_VALIDATE_INT);
        $fine = filter_var($data['fine'], FILTER_VALIDATE_FLOAT);
        $price_per_m3 = filter_var($data['price_per_m3'], FILTER_VALIDATE_FLOAT);
        $base_volume = filter_var($data['base_volume'], FILTER_VALIDATE_FLOAT);
        $base_price = filter_var($data['base_price'], FILTER_VALIDATE_FLOAT);

        if (!$expiry_date || !$expiry_mode || !$fine || !$price_per_m3
            || !($base_volume >= 0 && $base_volume <= PHP_INT_MAX) || !($base_price >= 0 && $base_price <= PHP_INT_MAX)
            || !in_array($expiry_date, range(1, 30)) || !in_array($expiry_mode, [1, 2])) {
            $this->jsonResult(false, "Por favor, preencha todos os campos correctamente !");
            return;
        }

        $update = (new \App\Models\Config())->update(compact("expiry_mode", "expiry_date",
            "fine", "price_per_m3", "base_price", "base_volume"))->execute();

        if ($update) {
            $_SESSION['config']["expiry_mode"] = $expiry_mode;
            $_SESSION['config']["expiry_date"] = $expiry_date;
            $_SESSION['config']["fine"] = $fine;
            $_SESSION['config']["price_per_m3"] = $price_per_m3;
            $_SESSION['config']["base_volume"] = $base_volume;
            $_SESSION['config']["base_price"] = $base_price;

            $this->jsonResult(true, "Atualizou todos os dados com sucesso");
            return;
        }

        $this->jsonResult(false, "Erro ou nada a atualizar, tente novamente");
    }

    public function update_password($data): void
    {
        $password = filter_var($data['password']);
        $new_password = filter_var($data['new_password']);

        if (!$password || !$new_password) {
            $this->jsonResult(false, "Por favor, preencha todos os campos correctamente !");
            return;
        }

        if (AdminSession::has()) {
            $id = "admin_id = '" . AdminSession::get()['id'] . "'";
        } elseif (WorkerSession::has()) {
            $id = "admin_id = '" . WorkerSession::get()['id'] . "'";
        } else {
            $id = "client_id = '" . UserSession::get()['id'] . "'";
        }

        $login = (new Login())->find("password")
            ->where($id)->execute();

        if (!password_verify($password, $login->password)) {
            $this->jsonResult(false, "A senha actual está errada, tente novamente !");
            return;
        }

        $update = (new Login())->update(["password" => password_hash($new_password, PASSWORD_DEFAULT)])
            ->where($id)->execute();

        if ($update) {
            $this->jsonResult(true, "Atualizou a senha com sucesso");
            return;
        }

        $this->jsonResult(false, "Erro ao atualizar os dados, tente novamente");
    }

    /**
     * @throws Exception
     */
    public function create_new_user($data)
    {
        $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $surname = filter_var($data['surname'], FILTER_SANITIZE_STRING);
        $phone = filter_var($data['phone'], FILTER_SANITIZE_STRING);

        if (!$name || !$surname || !$phone) {
            $this->jsonResult(false, "Por favor, preencha todos os campos correctamente !");
            return;
        }

        $role = 1;
        $username = strtolower($surname . $name) . rand(5, 100);
        $password = "sgfa" . rand(100, 999);

        $save = (new Admin())->save(compact("name", "surname", "phone", "role"))->execute();
        $save_login = (new Login())->save(["admin_id" => $save, "username" => $username,
            "password" => password_hash($password, PASSWORD_DEFAULT), "role" => $role])->execute();

        if ($save && $save_login) {
            $this->jsonResult(true,
                "Usuário criado com sucesso com username: $username e senha: $password", compact("username"));
            return;
        }

        $this->jsonResult(false, "Erro ao criar um novo usuário, tente novamente !");
    }

    public function update_user_info($data)
    {
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
        $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $surname = filter_var($data['surname'], FILTER_SANITIZE_STRING);
        $phone = filter_var($data['phone'], FILTER_VALIDATE_INT);

        if (!$id || !$name || !$surname || !$phone) {
            $this->jsonResult(false, "Por favor, preencha todos os campos correctamente !");
            return;
        }

        $update = (new Admin())
            ->update(compact("name", "surname", "phone"))->where("id = '$id'")->execute();

        if ($update) {
            $this->jsonResult(true, "Atualizou os dados com sucesso !");
            return;
        }

        $this->jsonResult(false, "Erro ou nada a atualizar, tente novamente !");
    }

    public function user_state($data)
    {
        $id = filter_var($data['id'], FILTER_VALIDATE_INT);
        $switch = filter_var($data['switch'], FILTER_VALIDATE_INT);

        if (!$id || !in_array($switch, [0,1])) {
            $this->jsonResult(false, "Por favor, selecione um administrador !");
            return;
        }

        $update = (new Admin())->update(["status" => (int)!$switch])
            ->where("id = '$id'")->execute();

        $update_login = (new Login())->update(["status" => (int)!$switch])
            ->where("admin_id = '$id'")->execute();

        if ($update && $update_login) {
            $this->jsonResult(true);
            return;
        }

        $this->jsonResult(false, "Erro ao atualizar o estado do administrador, tente novamente !");

    }

    public function update_account_info($data)
    {
        $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $surname = filter_var($data['surname'], FILTER_SANITIZE_STRING);
        $phone = filter_var($data['phone'], FILTER_VALIDATE_INT);
        $username = filter_var($data['username'], FILTER_SANITIZE_STRING);
        $repeat_username = filter_var($data['repeat-username'], FILTER_SANITIZE_STRING) ?? null;

        if (!$username || !$name || !$surname || !$phone) {
            $this->jsonResult(false, "Por favor, preencha todos os campos correctamente !");
            return;
        }

        if (!$repeat_username == null) {

            if ($username !== $repeat_username) {
                $this->jsonResult('w', "De forma a confirmar a troca de nome de usuário,
                deve preencher de forma igual os últimos 2 campos do formulário");
                return;
            }

            $update = (new Admin())
                ->update(compact("name", "surname", "phone"))
                ->where("id = '".AdminSession::get()['id']."'")->execute();

            $update_login = (new Login())->update(compact("username"))
                ->where("admin_id = '".AdminSession::get()['id']."'")->execute();

            if ($update && $update_login) {
                AdminSession::set([
                    "id" => AdminSession::get()['id'],
                    "name" => $name,
                    "surname" => $surname,
                    "phone" => $phone,
                    "role" => "0",
                    "username" => $username
                ]);
                $this->jsonResult(true);
                return;
            }

            $this->jsonResult(false, "Erro ao atualizar, tente novamente !");
            return;
        }

        $update = (new Admin())
            ->update(compact("name", "surname", "phone"))
            ->where("id = '".AdminSession::get()['id']."'")->execute();


        if ($update) {
            AdminSession::set([
                "id" => AdminSession::get()['id'],
                "name" => $name,
                "surname" => $surname,
                "phone" => $phone,
                "role" => "0",
                "username" => $username
            ]);
            $this->jsonResult(true);
            return;
        }

        $this->jsonResult(false, "Erro ou nada a atualizar, tente novamente !");
    }

    public function auto_sms()
    {
        if (!AdminSession::has() || $_SESSION['config']['admin_id'] != AdminSession::get()['id']) {
            $this->jsonResult(false, "Sem permissões para realizar a acção !");
            return;
        }

        $context = $_SESSION['config']['auto_sms'] ? 'Desligou' : 'Ligou';

        $update = (new \App\Models\Config())->update(["auto_sms" => $context == 'Ligou' ? 1 : 0])->execute();

        if ($update) {
            $_SESSION['config']['auto_sms'] = $context == 'Ligou' ? 1 : 0;
            $this->jsonResult(true, "$context as SMS's automáticas com sucesso");
            return;
        }

        $this->jsonResult(false, "Erro ao atualizar, tente novamente");
    }

}