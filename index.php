<?php

ob_start();
session_start();

require './vendor/autoload.php';

use CoffeeCode\Router\Router;

$router = new Router(site());

$router->namespace("App\Controllers\Pages");

/* Authentication Routes */
$router->get("/","Pub:login","pub.login");
$router->get("/secure/change-to-new-password","Pub:change","pub.change");

/* Administration pages */
$router->group("admin");
$router->get("/","Admin:home","admin.home");
$router->get("/invoices","Admin:invoices","admin.invoices");
$router->get("/receipts","Admin:receipts","admin.receipts");
$router->get("/sms","Admin:messages","admin.sms");
$router->get("/collaborators","Admin:collaborator","admin.collaborators");
$router->get("/financial","Admin:financial","admin.financial");
$router->get("/clients","Admin:clients","admin.clients");
$router->get("/config", "Admin:config", "admin.config");

/* Worker Pages */
$router->group("collab");
$router->get("/","Worker:index","worker.home");
$router->get("/invoice","Worker:invoices","worker.invoices");
$router->get("/receipt","Worker:receipts","worker.receipts");
$router->get("/sms","Worker:messages","worker.sms");
$router->get("/client","Worker:clients","worker.clients");
$router->get("/config", "Worker:config", "worker.config");

/* Users */
$router->group("client");
$router->get("/","User:home","user.home");
$router->get("/invoices","User:invoices","user.invoices");
$router->get("/receipts","User:receipts","user.receipts");
$router->get("/configurations","User:config","user.config");
$router->get("/pay-online","User:pay","user.pay");

$router->group(null);
$router->namespace("App\Controllers\Forms");

/* Invoice */
$router->post("/invoice/add","Invoices:add","invoice.add");
$router->post("/invoice/edit/{id}","Invoices:edit","invoice.edit");
$router->get("/invoice/clear-fine/{id}","Invoices:debt","invoice.clear_fine");
$router->get("/invoice/delete/{id}","Invoices:delete","invoice.delete");
$router->get("/invoice/cancel/{id}","Invoices:cancel","invoice.cancel");
$router->get("/invoice/reactivate/{id}","Invoices:reactivate","invoice.reactivate");
$router->get("/invoice/view/{id}","Invoices:visualize","invoice.preview");
$router->get("/invoice/print/{id}","Invoices:print","invoice.print");

/* Financial */
$router->post("/financial/expense/add","Financial:expense_add","financial.expenses.add");
$router->post("/financial/expense/delete","Financial:expense_delete","financial.expenses.delete");
$router->post("/financial/account/add", "Financial:account_add", "financial.accounts.add");

/* Receipt */
$router->post("/receipt/add","Receipts:add","receipt.add");
$router->get("/receipt/view/{id}","Receipts:visualize","receipt.visualizar");
$router->get("/receipt/print/{id}","Receipts:print","receipt.print");

/* Client */
$router->post("/client/add","Clients:add","client.add");
$router->post("/client/update","Clients:update","client.update");
$router->get("/client/status/{id}/{switch}","Clients:status","client.status");
$router->get("/client/historic/{id}", "Clients:historic", "client.historic");

/* Authentication */
$router->post("/login","Auth:login","auth.login");
$router->post("/change","Auth:change_pwd","auth.change");
$router->get("/access","Auth:home","auth.home");
$router->get("/logout","Auth:logout","auth.logout");

/* Config */
$router->post('/config/business/update', "Config:update_config", "config.business.update");
$router->get('/config/business/switch-auto-sms', "Config:auto_sms", "config.business.auto_sms");
$router->post('/config/account/update-password', "Config:update_password", "config.account.password");
$router->post('/config/account/update-info', "Config:update_account_info", "config.account.info");
$router->post('/config/users/new-user', "Config:create_new_user", "config.users.new_user");
$router->post('/config/users/update-info', "Config:update_user_info", "config.users.info");
$router->post('/config/users/switch-state/{id}/{switch}', "Config:user_state", "config.users.state");

/* SMS */
$router->post("/messages/send","Messages:send","admin.messages.send");
$router->post("/messages/request","Messages:request","admin.messages.request");

/* Payments */
$router->post("/secure/provider_payment/m-pesa","Payments:pay","mpesa.pay");

/* Routes dispatch */
$router->dispatch();

/* In case of error, redirect to home */
if($router->error()){
    $router->redirect("auth.home");
}

ob_end_flush();