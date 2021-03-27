<?php

namespace App\Models;

use alexcrisbrito\php_crud\Crud;

class Invoice extends Crud
{
    public function __construct()
    {
        parent::__construct("invoice", ["client_id", "amount", "expiry", "debt", "processed_by"]);
    }
}