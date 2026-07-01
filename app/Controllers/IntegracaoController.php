<?php

namespace App\Controllers;

use App\Core\View;

class IntegracaoController
{
    public function index()
    {
        View::render('placeholder/index', ['title' => 'Integração']);
    }
}
