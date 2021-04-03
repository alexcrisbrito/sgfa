<?php


namespace App\Installer\Controllers;


use App\Installer\Model\Admin;
use App\Installer\Model\Config;
use CoffeeCode\Router\Router;
use Exception;

class Form
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function index()
    {
        echo "index";
    }

    public function admin(array $data) :void
    {
        $data = filter_var_array($data, FILTER_SANITIZE_STRING);

        $trash = [];

        /* Check if data is not empty */
        foreach ($data as $field => $value) {
            if (empty($value) || !$value) {
                $trash[] = $field;
            }
        }

        if (!empty($trash))
            echo json_encode(["result" => false, "reason" => "Preencha corretamente o(s) campo(s):" . implode(",", $trash)]);

        $save = (new Admin())->save($data)->execute();
        if ($save) {
            $this->router->redirect("pub.config");
            exit;
        }

        echo json_encode(["result" => false, "reason" => "Erro ao processar, tente novamente !"]);
    }

    public function config(array $data) :void
    {
        $data['price_per_m3'] = filter_var($data['price_per_m3'], FILTER_VALIDATE_FLOAT);
        $data['expiry_mode'] = filter_var($data['expiry_mode'], FILTER_VALIDATE_INT);
        $data['day_expiry'] = filter_var($data['day_expiry'], FILTER_VALIDATE_INT);
        $data['payment_methods'] = filter_var(nl2br($data['payment_methods']), FILTER_SANITIZE_STRING);

        $trash = [];

        if (!$data['day_expiry'] >= 1 &&  $data['day_expiry'] <= 30) {
            $trash[] = $field;
        }

        /* Check if data is not empty */
        foreach ($data as $field => $value) {
            if (empty($value) || !$value) {
                if(!in_array($field, $trash)) $trash[] = $field;
            }
        }

        if (!empty($trash))
            echo json_encode(["result" => false, "reason" => "Preencha corretamente o(s) campo(s):" . implode(",", $trash)]);

        $save = (new Config())->save($data)->execute();
        if ($save) {
            $this->router->redirect("pub.thanks");
            exit;
        }

        echo json_encode(["result" => false, "reason" => "Erro ao processar, tente novamente !"]);

    }
}