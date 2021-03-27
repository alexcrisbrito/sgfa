<?php

namespace App\Controllers\Forms;

use App\Controllers\BaseController;
use App\Helpers\AdminSession;
use App\Helpers\SessionManager;
use App\Helpers\UserSession;
use App\Helpers\WorkerSession;
use App\Models\Admin;
use App\Models\Admin_Login;
use App\Models\Client;
use App\Models\Client_Login;
use App\Models\Login;

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

            echo ajax("msg", ["type" => "alert-warning", "msg" => "[!] Por favor, preencha todos os campos corretamente."]);
            return;
        }

        $exists = (new Login())->find()->where("username = '{$id}'")->execute();

        if ($exists) {

            switch ($exists->role) {
                case 2:
                    $credentials = (new Client_Login())->find()->where("id = '{$exists->sub_id}'")->execute();
                    $data = (new Client())->find()->where("id = '{$credentials->client_id}'")->execute(\PDO::FETCH_ASSOC);
                    $type = 'user';
                    break;

                case 1:
                case 0:
                    $credentials = (new Admin_Login())->find()->where("id = '{$exists->sub_id}'")->execute();
                    $data = (new Admin())->find()->where("id = '{$credentials->admin_id}'")->execute(\PDO::FETCH_ASSOC);
                    $type = ($exists->role == 0 ? 'admin' : 'worker');
                    break;
            }

            if (password_verify($pwd, $credentials->password)) {

                if($exists->first_login === true) SessionManager::setIsFirstLogin();
                SessionManager::set($type, $data);

                echo ajax("redirect", [$this->router->route("home")]);
                return;
            }
        }

        echo ajax("msg", ["type" => "alert-danger", "msg" => "[!] Os dados estão incorretos."]);
    }

    public function logout(): void
    {
        SessionManager::unset();
        $this->router->redirect("pub.login");
    }

    public function home()
    {
        if (SessionManager::isFirstLogin()) {
            $this->router->redirect("pub.change");
        }

        if (UserSession::has()) {

            $this->router->redirect("user.home");

        } elseif (AdminSession::has()) {

            $this->router->redirect("admin.home");

        } elseif (WorkerSession::has()) {

            $this->router->redirect("worker.home");

        }

    }

    public function change_pwd($data): void
    {
        $password = filter_var($data["new_passwd"], FILTER_SANITIZE_STRING);

        if (!$password) {
            echo ajax("msg", ["type" => "alert-danger", "msg" => "[!] Por favor, preencha o campo de nova senha corretamente."]);
            return;
        }

        if(SessionManager::has()) {

            if (UserSession::has()) {
                $updated = (new Client_Login())->update(["password" => password_hash($password, PASSWORD_DEFAULT), "first_login" => 0])
                    ->where("id = '" . UserSession::get()['id'] . "'")->execute();
            } elseif (AdminSession::has() || WorkerSession::has()) {
                $updated = (new Admin_Login())->update(["password" => password_hash($password, PASSWORD_DEFAULT), "first_login" => 0])
                    ->where("id = '" . AdminSession::get()['id'] . "'")->execute();
            }

            if ($updated) {
                SessionManager::unsetFirstLogin();
                echo ajax("redirect", [$this->router->route("home")]);
                return;
            }
        }

        echo ajax("msg", ["type" => "alert-danger", "msg" => "[!] Erro ao alterar a sua password, tente novamente."]);
    }
}