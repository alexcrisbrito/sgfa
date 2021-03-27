<?php
ob_start();
session_start();

require "./vendor/autoload.php";

use CoffeeCode\Router\Router;

$router = new Router(site());
$router->namespace("App\Controllers");

/* Authentication Routes */
$router->get("/","Pages\Nav:login","pub.login");
$router->post("/login","Forms\Auth:login","auth.login");
$router->get("/nova-senha","Pages\Nav:change","pub.change");
$router->post("/change","Forms\Auth:change_pwd","auth.change");
$router->get("/access","Pages\Auth:home","home");
$router->get("/sair","Forms\Auth:logout","auth.logout");

/* Administration pages */
$router->group("admin");
$router->get("/home","Pages\Admin:home","admin.home");
$router->get("/faturas","Pages\Admin:invoices","admin.facturas");
$router->get("/recibos","Pages\Admin:receipts","admin.recibos");
$router->get("/mensagens","Pages\Admin:messages","admin.mensagens");
$router->get("/configurar","Pages\Admin:colab","admin.colab");
$router->get("/financeiro","Pages\Admin:financial","admin.financeiro");
$router->get("/clientes","Pages\Admin:clients","admin.clientes");

/* Worker Pages */
$router->group("func");
$router->get("/home","Pages\Worker:index","worker.home");
$router->get("/faturas","Pages\Worker:invoices","worker.facturas");
$router->get("/recibos","Pages\Worker:receipts","worker.recibos");
$router->get("/mensagens","Pages\Worker:messages","worker.mensagens");
$router->get("/clientes","Pages\Worker:clients","worker.clientes");

/* Users */
$router->group(null);
$router->get("/home","Pages\Nav:home","user.home");
$router->get("/faturas","Pages\Nav:invoices","user.facturas");
$router->get("/recibos","Pages\Nav:receipts","user.recibos");

/* Invoice */
$router->post("/facturas/emitir","Forms\Invoices:add","admin.facturas.emitir");
$router->post("/facturas/editar/{id}","Forms\Invoices:edit","admin.facturas.editar");
$router->post("/facturas/divida","Forms\Invoices:debt","admin.facturas.divida");
$router->post("/facturas/apagar","Forms\Invoices:delete","admin.facturas.apagar");
$router->post("/facturas/cancelar","Forms\Invoices:cancel","admin.facturas.cancelar");
$router->get("/facturas/visualizar/{id}","Forms\Invoices:visualize","admin.facturas.visualizar");
$router->get("/facturas/imprimir/{id}","Forms\Invoices:print","admin.facturas.imprimir");

/* Expenses */
$router->post("/despesas/registrar","Forms\Financial:expense_add","admin.financeiro.despesas.emitir");
$router->post("/despesas/apagar","Forms\Financial:expense_delete","admin.financeiro.despesas.apagar");

/* Receipt */
$router->get("/recibos/visualizar/{id}","Forms\Receipts:visualize","admin.recibos.visualizar");
$router->get("/recibos/imprimir/{id}","Forms\Receipts:print","admin.recibos.imprimir");
$router->post("/recibos/emitir","Forms\Receipts:add","admin.recibos.emitir");

/* Client */
$router->post("/clientes/cadastrar","Forms\Clients:add","admin.clientes.cadastrar");
$router->get("/clientes/editar/{id}","Forms\Clients:get","admin.clientes.editar");
$router->post("/clientes/atualizar","Forms\Clients:update","admin.clientes.atualizar");
$router->post("/clientes/estado","Forms\Clients:status","admin.clientes.estado");

/* SMS */
$router->post("/messages/send","Messages:send","admin.messages.send");


/* Routes dispatch */
$router->dispatch();

/* In case of error, redirect to home */
if($router->error()){
    $router->redirect("home");
}

ob_end_flush();
