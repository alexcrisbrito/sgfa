<?php

namespace App\Models;

use Alexcrisbrito\Php_crud\Crud;

class Invoice extends Crud
{
    public function __construct()
    {
        parent::__construct("invoice", ["client_id", "amount", "expiry_date", "debt", "processed_by"]);
    }
}