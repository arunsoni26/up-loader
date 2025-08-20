<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerDocument;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $customers = Customer::all();
        $users = User::all();
        $customerDocs = CustomerDocument::join('customers', 'customers.id', 'customer_documents.customer_id')
            ->where('customers.status', 1)->get();
        return view('admin.dashboard', compact('customers', 'users', 'customerDocs'));
    }
}
