<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
session_start();
class CustomerController extends Controller
{
    
    public function index()
    {
        $customer = Customer::all();
        return view('admin.user.user_list', compact('customer'));

    }
}