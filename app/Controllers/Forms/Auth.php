<?php

namespace App\Controllers\Forms;

use App\Controllers\BaseController;
use App\Helpers\AdminSession;
use App\Helpers\SessionManager;
use App\Helpers\UserSession;
use App\Helpers\WorkerSession;
use App\Models\Accounts;
use App\Models\Admin;
use App\Models\Admin_Login;
use App\Models\Client;
use App\Models\Client_Login;
use App\Models\Config;
use App\Models\Login;
use PDO;

class Auth extends BaseController
{
    public function __construct($router)
    {
        parent::__construct($router);
    }

    public function login($data): void
    {
        $id = filter_var($data["id"], FILTER_SANITIZE_STRING);
        $pwd = filter_var($data["pwd"], FILTER_DEFAULT);

        if (!$id or !$pwd) {
            echo ajax("msg", ["type" => "alert-warning", "msg" => "Por favor, preencha todos os campos corretamente."]);
            return;
        }

        $login = (new Login())->find()->where("username = '$id'")->execute();


        if ($login) {

            if ($login->status == 0) {
                echo ajax("msg", ["type" => "alert-danger", "msg" =>
                    "A sua conta está suspensa, por favor contacte a nossa linha do cliente para esclarecimentos!"]);
                return;
            }

            if ($login->role == 2) {
                $data = (new Client())->find()->where("id = '$login->client_id'")->execute(PDO::FETCH_ASSOC);
                $type = 'user';

            } else {
                $data = (new Admin())->find()->where("id = '$login->admin_id'")->execute(PDO::FETCH_ASSOC);
                $type = $login->role == 0 ? 'admin' : 'worker';
                $_SESSION['config'] = (new Config())->find()->execute(PDO::FETCH_ASSOC);
                $_SESSION['accounts'] = (new Accounts())->find()->execute(PDO::FETCH_ASSOC, true);
            }

            if (password_verify($pwd, $login->password)) {

                if ($login->first_login == 1) SessionManager::setIsFirstLogin();
                SessionManager::set($type, $data);

                echo ajax("redirect", [$this->router->route("auth.home")]);
                return;
            }
        }

        echo ajax("msg", ["type" => "alert-danger", "msg" => "Os dados estão incorretos."]);
    }

    public function logout(): void
    {
        SessionManager::unset();
        SessionManager::unsetFirstLogin();
        if (isset($_SESSION['config'])) unset($_SESSION['config']);
        if (isset($_SESSION['accounts'])) unset($_SESSION['accounts']);
        $this->router->redirect("pub.login");
    }

    public function home()
    {
        if (SessionManager::isFirstLogin()) {
            $this->router->redirect("pub.change");
            exit;
        }

        if (UserSession::has()) {
            $this->router->redirect("user.home");
            exit;
        }

        if (AdminSession::has()) {
            $this->router->redirect("admin.home");
            exit;
        }

        $this->router->redirect("worker.home");
    }

    public function change_pwd($data): void
    {
        $password = filter_var($data["password"]);
        $repeat_password = filter_var($data['repeat-password']);

        if (!$password || !$repeat_password || $password !== $repeat_password) {
            echo ajax("msg", ["type" => "alert-danger", "msg" => "Por favor, preencha os campos corretamente."]);
            return;
        }

        if (SessionManager::has()) {

            if (UserSession::has()) {

                $check = (new Login())->find("password")
                    ->where("client_id = '" . UserSession::get()['id'] . "'")->execute();

                if (password_verify($password, $check->password)) {
                    echo ajax("msg", ["type" => "alert-danger", "msg" =>
                        "Por favor, utilize uma senha diferente da que foi gerada pelo sistema !"]);
                    return;
                }

                $updated = (new Login())
                    ->update(["password" => password_hash($password, PASSWORD_DEFAULT), "first_login" => 0])
                    ->where("client_id = '". UserSession::get()['id'] ."'")->execute();
            }

            if (AdminSession::has() || WorkerSession::has()) {
                $check = (new Login())->find("password")
                    ->where("admin_id = '". (AdminSession::get()['id'] ?? WorkerSession::get()['id']) ."'")
                    ->execute();

                if (password_verify($password, $check->password)) {
                    echo ajax("msg", ["type" => "alert-danger", "msg" =>
                        "Por favor, utilize uma senha diferente da que foi gerada pelo sistema !"]);
                    return;
                }

                $updated = (new Login())
                    ->update(["password" => password_hash($password, PASSWORD_DEFAULT), "first_login" => 0])
                    ->where("admin_id = '". (AdminSession::get()['id'] ?? WorkerSession::get()['id']) ."'")
                    ->execute();
            }

            if ($updated) {
                SessionManager::unsetFirstLogin();
                echo ajax("redirect", [$this->router->route("home")]);
                return;
            }
        }

        echo ajax("redirect", [$this->router->route("home")]);
    }
}