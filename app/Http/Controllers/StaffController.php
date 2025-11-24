<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{

    public function index()
    {
        $user = User::where('role', '=', 2)->get();
        $viewData = [
            'user' => $user,
        ];
        return view('admin.staff.staff_list', $viewData);
    }

    public function create()
    {
        return view('admin.staff.staff_add');
    }

    public function store(Request $request)
    {
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
        $user->role = 2;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $nhanvien = new Staff;
        $nhanvien->user_id = $user->id;
        $nhanvien->name = $request->name;
        $nhanvien->phone = $request->phone;
        $nhanvien->birth_day = $request->birth_day;
        $nhanvien->gender = $request->gender;
        $nhanvien->address = $request->address;
        $nhanvien->save();
        return redirect()->route('admin.staff.index')->with('thongbao', 'Thêm thành công!');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $viewData = [
            'user' => $user,
        ];
        return view('admin.staff.staff_edit', $viewData);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        $staff = Staff::where('user_id', '=', $id)->get();
        foreach ($staff as $item) {
            $item->name = $request->name;
            $item->phone = $request->phone;
            $item->birth_day = $request->birth_day;
            $item->gender = $request->gender;
            $item->address = $request->address;
            $item->save();
        }

        return redirect()->route('admin.staff.index')->with('Thông báo', 'Sửa thành công!');
    }

    public function destroy($id)
    {
        $staff = Staff::where('user_id', '=', $id)->get();
        foreach ($staff as $item) {
            $item->delete();
        }
        User::destroy($id);
        return redirect()->back()->with('Thông báo', 'Xoá thành công!');
    }

    public function getChangePassword($id)
    {
        $user = User::find($id);
        $user->password = bcrypt('12345678');
        return redirect()->back()->with('Thông báo', 'Mật khẩu được trả về "12345678" !');
    }
}
