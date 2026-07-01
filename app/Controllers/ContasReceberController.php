<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\View;

class ContasReceberController extends Controller
{
    public function index()
    {
        View::render("placeholder/index", ["title" => "Contas a Receber"]);
    }
}
