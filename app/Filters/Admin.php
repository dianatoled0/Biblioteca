<?php
namespace App\Controllers;

use App\Controllers\BaseController;

class Admin extends BaseController
{
    public function index()
    {
        // Renderiza la vista dashboard usando el layout
        return view('admin/dashboard');
    }
}

