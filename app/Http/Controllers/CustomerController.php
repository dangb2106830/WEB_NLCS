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

    public function getVerifyEmail(){
        return view('front.verifyEmail');
    }

    public function verifyEmailCustomer(Request $request){
        
        $data = $request->all();
        $now = time();
        $get_email_customer = User::where('email', $data['email'])->first();
        // dd($get_email_customer);
        if($get_email_customer){
            return back()->with('error','Email đã tồn tại!');
        }
        else{
            $verification_code= strtoupper(Str::random(10));
            $to_name="Đồ gia dụng";
            $to_mail=$data['email'];
            $title_mail = "Mã Xác Thực Từ Đồ gia dụng";
            $emailData = array("name" => "Đồ gia dụng", "body" => $verification_code);
            $verification[] = array(
                'verification_time' => $now + 300,
                'verification_code' => $verification_code,
                'email' => $to_mail,
            );
            Session::put('email',$verification);
            Mail::send('front.notifyEmail',  $emailData, function($message) use ($to_name,$to_mail,$title_mail ){
                $message->to($to_mail)->subject($title_mail );//send this mail with subject
                $message->from($to_mail, $to_name );//send from this mail
            });
            return redirect()->route('index.register')->with('message','Chúng tôi đã gửi mã xác minh vào email của bạn, hãy nhập mã xác minh để đăng ký tài khoản!');
        }
    }

    public function postRegister(Request $request)
    {
      
        $data=$request->all();
        $now=time();
        $verification=Session::get('email');
        if(!isset($verification)){
            return back()->with('error','Nhập email của bạn dã xác thực để đăng ký!');
        }else{
            foreach($verification as $key=>$value){
                $verification_time=$value['verification_time'];
                $verification_code=$value['verification_code'];
                $verification_email=$value['email'];
                break;
            }
            if($verification_code != $data['customer_verification_code_register'] || $verification_email != $data['email']){
                return back()->with('error','Mã xác minh hoặc email không chính xác!');
            }elseif($now > $verification_time){
                Session::forget('verification_email_customer');
                return back()->with('error','Mã xác minh đã hết hạn!');
            }else{     
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
                Session::forget('verification_email_customer');
                return Redirect::to('/login')->with('message','Đăng ký tài khoản thành công! Vui lòng đăng nhập');
            }
        }

    }


}