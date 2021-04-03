<?php

/* Let's check if the system is already installed */
if (file_exists("./install/")) {
    header("Location: /install");
    exit;
}

ob_start();
session_start();

require './vendor/autoload.php';

use CoffeeCode\Router\Router;

$router = new Router(site());

$router->namespace("App\Controllers\Pages");

/* Authentication Routes */
$router->get("/","Pub:login","pub.login");
$router->get("/access","Auth:home","home");
$router->get("/sair","Auth:logout","auth.logout");
$router->get("/nova-senha","Pub:change","pub.change");

/* Administration pages */
$router->group("admin");
$router->get("/home","Admin:home","admin.home");
$router->get("/faturas","Admin:invoices","admin.facturas");
$router->get("/recibos","Admin:receipts","admin.recibos");
$router->get("/mensagens","Admin:messages","admin.mensagens");
$router->get("/configurar","Admin:colab","admin.colab");
$router->get("/financeiro","Admin:financial","admin.financeiro");
$router->get("/clientes","Admin:clients","admin.clientes");

/* Worker Pages */
$router->group("func");
$router->get("/home","Worker:index","worker.home");
$router->get("/faturas","Worker:invoices","worker.facturas");
$router->get("/recibos","Worker:receipts","worker.recibos");
$router->get("/mensagens","Worker:messages","worker.mensagens");
$router->get("/clientes","Worker:clients","worker.clientes");

/* Users */
$router->group(null);
$router->get("/home","Nav:home","user.home");
$router->get("/faturas","Nav:invoices","user.facturas");
$router->get("/recibos","Nav:receipts","user.recibos");


$router->namespace("App\Controllers\Forms");
/* Invoice */
$router->post("/facturas/emitir","Invoices:add","admin.facturas.emitir");
$router->post("/facturas/editar/{id}","Invoices:edit","admin.facturas.editar");
$router->post("/facturas/divida","Invoices:debt","admin.facturas.divida");
$router->post("/facturas/apagar","Invoices:delete","admin.facturas.apagar");
$router->post("/facturas/cancelar","Invoices:cancel","admin.facturas.cancelar");
$router->get("/facturas/visualizar/{id}","Invoices:visualize","admin.facturas.visualizar");
$router->get("/facturas/imprimir/{id}","Invoices:print","admin.facturas.imprimir");

/* Expenses */
$router->post("/despesas/registrar","Financial:expense_add","admin.financeiro.despesas.emitir");
$router->post("/despesas/apagar","Financial:expense_delete","admin.financeiro.despesas.apagar");

/* Receipt */
$router->get("/recibos/visualizar/{id}","Receipts:visualize","admin.recibos.visualizar");
$router->get("/recibos/imprimir/{id}","Receipts:print","admin.recibos.imprimir");
$router->post("/recibos/emitir","Receipts:add","admin.recibos.emitir");

/* Client */
$router->post("/clientes/cadastrar","Clients:add","admin.clientes.cadastrar");
$router->get("/clientes/editar/{id}","Clients:get","admin.clientes.editar");
$router->post("/clientes/atualizar","Clients:update","admin.clientes.atualizar");
$router->post("/clientes/estado","Clients:status","admin.clientes.estado");

/* Authentication */
$router->post("/login","Auth:login","auth.login");
$router->post("/change","Auth:change_pwd","auth.change");

///* SMS */
//$router->post("/messages/send","Messages:send","admin.messages.send");


/* Routes dispatch */
$router->dispatch();

/* In case of error, redirect to home */
//if($router->error()){
//    $router->redirect("home");
//}

ob_end_flush();
