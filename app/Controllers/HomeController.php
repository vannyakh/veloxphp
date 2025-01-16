<?php

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return $this->render('home', [
            'title' => 'Welcome to VeloxPHP'
        ]);
    }

    public function api()
    {
        return $this->json([
            'message' => 'Welcome to VeloxPHP API'
        ]);
    }
} 