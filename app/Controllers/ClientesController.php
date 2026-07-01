<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;

class ClientesController extends Controller
{
    public function index()
    {
        View::render('placeholder/index', ['title' => 'Clientes']);
    }
}
