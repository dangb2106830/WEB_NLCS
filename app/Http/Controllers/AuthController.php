<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index() {
        return view('admin.index');
    }

    public function adminLogin() {
        return view('admin.layouts.login');
    }

     public function adminLogout() {
        Auth::logout();
        return redirect()->route('admin.login');
    }


    public function postAdminLogin(Request $request) {
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Bạn chưa nhập email',
            'password.required' => 'Bạn chưa nhập mật khẩU',
        ]);

        $remember = $request->has('remember') ? true : false;

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            return redirect()->route('admin.index');
            
        } else {
            return redirect()->back()->with('thongbao', 'Đăng nhập thất bại!');
        }
    }
   
}