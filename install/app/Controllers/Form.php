<?php


namespace App\Installer\Controllers;


use App\Installer\Model\Admin;
use App\Installer\Model\Admin_Login;
use App\Installer\Model\Config;
use App\Installer\Model\Login;
use CoffeeCode\Router\Router;


class Form
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function index($data)
    {
        if (isset($_SESSION['stages'])) {
            $this->router->redirect("pub.users");
            exit;
        }

        $this->router->redirect("pub.users");

        $activation_code = filter_var($data['activation_key'], FILTER_SANITIZE_STRING);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $_ENV['LICENSE_API_GET'].$activation_code);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = json_decode(curl_exec($curl));

        var_dump($response);

        if ($response->result == true) {
            $path = dirname(__DIR__, 3) . "\.env";
            $contents = file_get_contents($path);
            $contents = str_replace("LICENSE_CODE=", "LICENSE_CODE=$response->code", $contents);
            $contents = str_replace("LICENSED_TO=", 'LICENSED_TO="'.$response->licensed_to.'"', $contents);
            $contents = str_replace("LICENSE_EXPIRY_DATE=", "LICENSE_EXPIRY_DATE=$response->expiry_date", $contents);

            file_put_contents($path, $contents);
            $this->router->redirect("pub.users");

            exit;
        }

        $_SESSION['alert'] = [
            "type" => "danger",
            "msg" => "A chave de ativação introduzida está incorreta, tente novamente."
        ];
        $this->router->redirect("pub.index");
        exit;
    }

    public function admin(array $data) :void
    {
        if (isset($_SESSION['stages']) && $_SESSION['stages'] !== 2) {
            $this->router->redirect("pub.admin");
            exit;
        }

        $data = filter_var_array($data, FILTER_SANITIZE_STRING);

        $trash = [];

        /* Check if data is not empty */
        foreach ($data as $field => $value) {
            if (empty($value) || !$value) {
                $trash[] = $field;
            }
        }

        if (!empty($trash)) {
            $_SESSION['alert'] = [
                "type" => "warning",
                "msg" => "Preencha corretamente o(s) campo(s):" . implode(",", $trash)
            ];
            $this->router->redirect("pub.users");
            exit;
        }

        $save_admin = (new Admin())->save([
            "name" => $data['name'],
            "surname" => $data['surname'],
            "role" => 0
        ])->execute();

        $save_login = (new Login())->save([
            "admin_id" => 1,
            "username" => $data['username'],
            "password" => password_hash($data['password'], PASSWORD_DEFAULT),
            "role" => 0,
            "first_login" => false,
            "status" => 1
        ])->execute();

        if ($save_admin && $save_login) {
            $this->router->redirect("pub.config");
            $_SESSION['stages'] = 2;
            exit;
        }

        $_SESSION['alert'] = [
            "type" => "warning",
            "msg" => "Erro ao processar, tente novamente !"
        ];
        $this->router->redirect("pub.users");
        exit;
    }

    public function config(array $data) :void
    {
        if (isset($_SESSION['stages']) && $_SESSION['stages'] !== 3) {
            $this->router->redirect("pub.thanks");
            exit;
        }

        $data['price_per_m3'] = filter_var($data['price_per_m3'], FILTER_VALIDATE_FLOAT);
        $data['expiry_mode'] = filter_var($data['expiry_mode'], FILTER_VALIDATE_INT);
        $data['expiry_date'] = filter_var($data['expiry_date'], FILTER_VALIDATE_INT);
        $data['fine'] = filter_var($data['fine'], FILTER_VALIDATE_FLOAT);

        $trash = [];

        if (!$data['expiry_date'] >= 1 && $data['expiry_date'] <= 30) {
            $trash[] = 'expiry_date';
        }

        /* Check if data is not empty */
        foreach ($data as $field => $value) {
            if (empty($value)) {
                if(!in_array($field, $trash)) $trash[] = $field;
            }
        }

        if (!empty($trash)) {
            $_SESSION['alert'] = [
                "type" => "warning",
                "msg" => "Preencha corretamente o(s) campo(s): ". implode(",", $trash)
            ];
            $this->router->redirect("pub.config");
            exit;
        }

        $save = (new Config())->save($data)->execute();
        if ($save) {
            $path = dirname(__DIR__, 3) . "\.env";
            $contents = file_get_contents($path);
            $contents = str_replace("APP_STATE=INSTALL", "APP_STATE=PROD", $contents);
            file_put_contents($path, $contents);
            $_SESSION['stages'] = 3;
            $this->router->redirect("pub.thanks");
            exit;
        }

        $_SESSION['alert'] = [
            "type" => "warning",
            "msg" => "Erro ao processar, tente novamente !"
        ];
        $this->router->redirect("pub.config");
        exit;
    }
}