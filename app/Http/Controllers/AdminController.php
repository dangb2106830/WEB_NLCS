<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Staff;

class AdminController extends Controller
{
    public function show()
    {
        $customer = Customer::all();
        $staff = Staff::all();
        $order = Order::all();
        $blog = Blog::all();
        $slide = Slide::all();
        $category= Category::all();
        $brand= Brand::all();
        $product = Product::all();
        return view('admin.index', compact('product','category','brand','blog','slide','order','staff', 'customer'));
    }
   
}
