<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // You can add logic to fetch dashboard data here
        return view('customers.dashboard');
    }
} 