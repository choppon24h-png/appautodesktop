<?php

namespace App\Controllers;

use App\Core\View;

class FaturamentoController
{
    public function index()
    {
        View::render('placeholder/index', ['title' => 'Faturamento']);
    }
}
