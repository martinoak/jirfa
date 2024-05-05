<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $customers = Customer::all();

        return view('admin.index', compact('customers'));
    }
}
