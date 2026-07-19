<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Customer;
use App\Models\Reference;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin.index', [
            'customers' => Customer::latest()->get(),
            'referenceCount' => Reference::count(),
            'certificateCount' => Certificate::count(),
        ]);
    }
}
