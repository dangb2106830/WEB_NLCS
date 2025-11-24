<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Slide;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Order_Detail;
use App\Models\Product_Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class PageController extends Controller
{
   
    public function getLogin()
    {
        return view('front.layouts.login');
    }

}