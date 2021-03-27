<?php

namespace App\Models;


use alexcrisbrito\php_crud\Crud;

class Client extends Crud
{
    public function __construct()
    {
        parent::__construct("client", ["name", "surname", "phone", "address"]);
    }

}