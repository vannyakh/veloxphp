<?php

namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalUsers' => User::count(),
            'latestUsers' => User::latest()->limit(5)->get(),
            'title' => 'Dashboard'
        ];

        return $this->view('admin::dashboard', $data);
    }
} 