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

    public function adminProfile() {
        return view('admin.profile.index');
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

    public function adminLogout() {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function userLogout() {
        Auth::logout();
        return redirect()->route('index.login');
    }

    public function postUserLogin(Request $request) {
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Bạn chưa nhập email',
            'password.required' => 'Bạn chưa nhập mật khẩU',
        ]);
        // $user = User::where('email', $request->email)->first();
        // $user->password = Hash::make('It@@246357');
        // $user->save();
        // // dd($user);
        // if ($user) {
        //     if (Hash::check($request->password, $user->password)) {
        //         return redirect()->route('index');
        //     } else {
        //         return redirect()->back()->with('thongbao', 'Tài khoản hoặc mật khẩu sai!');
        //     }
        // } else {
        //     return redirect()->back()->with('thongbao', 'Tài khoản hoặc mật khẩu sai!');
        // }
        $remember = $request->has('remember') ? true : false;
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            return redirect()->route('index');
        } else {
            return redirect()->back()->with('thongbao', 'Tài khoản hoặc mật khẩu sai!');
        }
    }

    public function postUserRegister(Request $request) {
      $this->validate(
        $request,
        [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6|max:32',
            'phone' => 'required',
            'birth_day' => 'required',
            'gender' => 'required',
            'address' => 'required',
        ],
        [
            "name.required" => "Hãy nhập tên!",
            "email.required" => "Hãy nhập email!",
            "email.unique" => "Email đã tồn tại!",
            "password.required" => "Hãy nhập password!",
            "password.min" => "Độ dài mật khẩu lớn hơn 6!",
            "password.max" => "Độ dài mật khẩu nhỏ hơn 32!",
            "phone.required" => "Hãy nhập số điện thoại!",
            "birth_day.required" => "Hãy nhập ngày sinh!",
            "gender.required" => "Hãy chọn giới tính!",
            "address.required" => "Hãy nhập địa chỉ!",
        ]
        );

        $user = new User;
        $user->role = 3;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $kh = new Customer();
        $kh->user_id = $user->id;
        $kh->name = $request->name;
        $kh->phone = $request->phone;
        $kh->birth_day = $request->birth_day;
        $kh->gender = $request->gender;
        $kh->address = $request->address;
        $kh->save();
        return redirect()->route('index.login')->with('thongbao', 'Đăng ký thành công thành công!');
    }
    
    public function editProfile(Request $request) {
        $customer = Customer::find(Auth::user()->customer->id);
        $this->validate($request,[
            'name' => 'required',
            'phone' => 'required',
            'birth_day' => 'required',
            'address' => 'required',
        ], [
            'name.required' => 'Bạn chưa nhập họ tên',
            'phone.required' => 'Bạn chưa nhập số điện thoại',
            'birth_day.required' => 'Bạn chưa nhập ngày sinh',
            'address.required' => 'Bạn chưa nhập địa chỉ',
        ]);
        
        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->birth_day = $request->birth_day;
        $customer->address = $request->address;
        $customer->update();
        return redirect()->back()->with('success', 'Cập nhật thông tin thành công');
    } 

    public function getProfile() {
        $order = Order::where('customer_id', '=', Auth::user()->customer->id)->orderBy('id', 'desc')->get();
        $viewData = [
            'order' => $order,
        ];
        return view('pages.profile', $viewData);
    } 
    
    public function postUserPassword(Request $request) {
        $user = Auth::user();

        if(!(Hash::check($request->oldPassword, $user->password))) {
    		return redirect()->back()->with('loi', 'Sai mật khẩu cũ!');

    	} else if(strcmp($request->oldPassword, $request->password) == 0){
    		return redirect()->back()->with('loi', 'Mật khẩu mới trùng mật khẩu cũ!');

    	}
    	//change password
    	$user->password = bcrypt($request->password);
    	$user->update();
        return redirect()->back()->with('success', 'Thay đổi mật khẩu thành công!');
    }
}