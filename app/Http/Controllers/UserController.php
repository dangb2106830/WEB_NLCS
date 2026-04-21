<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // $users = User::all();
        // return view('admin.user.user_list', compact('users'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
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
}
