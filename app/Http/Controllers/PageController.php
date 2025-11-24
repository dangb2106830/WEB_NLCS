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
   
    public function index()
    {
        $product = Product::orderBy('id', 'desc')->paginate(4);
        $pro = Product::all();
        $blog = Blog::all();
        $slide = Slide::all();
        $brand = Brand::all();
        $sale = Product::where('discount', '>', 0)->paginate(4);
        $status = Product::where('status', 1)->get();
        $viewData = [
            'product' => $product,
            'blog' => $blog,
            'slide' => $slide,
            'sale' => $sale,
            'status' => $status,
            'pro' => $pro,
            'brand'=> $brand
        ];
        return view('front.index', $viewData);
    }
    
    public function getProduct()
    {
        $product = Product::orderBy('id', 'desc')->get();
        $pro = Product::all();
        $category = Category::all();
        $brand = Brand::all();
        $viewData = [
            'product' => $product,
            'category' => $category,
            'brand' => $brand,
            'pro' => $pro

        ];
        return view('front.product', $viewData);
    }

    public function getCategory($slug, $id)
    {
        $brand = Brand::all();
        $pro = Product::all();
        $category = Category::all();
        $cate = Category::find($id);
        $viewData = [
            'cate' => $cate,
            'brand' => $brand,
            'category' => $category,
            'pro' => $pro

        ];
        return view('front.category', $viewData);
    }

    public function getBrand($slug, $id)
    {
        $pro = Product::all();
        $bra = Brand::find($id);
        $brand = Brand::all();
        $category = Category::all();
        $viewData = [
            'bra' => $bra,
            'brand' => $brand,
            'category' => $category,
            'pro' => $pro
        ];
        return view('front.brand', $viewData);
    }

    public function getProductDetail( $product_slug, $product_id)
    {
        $product = Product::find($product_id);
        $products = Product::find($product_id)->product_image;
        $pro = Product::all();
        $brand = Brand::all();
        $viewData = [
            'product' => $product,
            'pro' => $pro,
            'brand'=> $brand,
            'products' => $products
        ];
        return view('front.pro-detail', $viewData);
    }

    public function getGallery()
    {
        return view('front.gallery');
    }

    public function getContact()
    {
        $slide = Slide::all();
        $pro = Product::all();
        $brand = Brand::all();
        $viewData = [
            'slide' => $slide,
            'brand'=> $brand,
            'pro' => $pro
        ];
        return view('front.contact-us', $viewData);
    }
    
    public function getAccount()
    {
        $order = Order::where('customer_id', '=', Auth::user()->customer->id)->orderBy('id', 'desc')->get();
        $slide = Slide::all();
        $brand = Brand::all();
        $pro = Product::all();
        $viewData = [
            'slide' => $slide,
            'order' => $order,
            'pro' => $pro,
            'brand' => $brand
        ];
        return view('front.my-account', $viewData);
    }
    public function getSlide()
    {
        $product = Product::orderBy('id', 'desc')->get();
        $pro = Product::all();
        $brand = Brand::all();
        $viewData = [
            'product' => $product,
            'pro' => $pro,
            'brand'=>$brand
        ];
        return view('front.layouts.layout', $viewData);
    }
    
    public function getAbout()
    {
        $pro = Product::all();
        $blog = Blog::all();
        $slide = Slide::all();
        $brand = Brand::all();
        $viewData = [
            'blog' => $blog,
            'slide' => $slide,
            'pro' => $pro,
            'brand'=> $brand
        ];
        return view('front.about', $viewData);
    }

    public function getBlog($blog_slug,$blog_id)
    {
        $pro = Product::all();
        $blog = Blog::all();
        $blo = Blog::find($blog_id);
        $slide = Slide::all();
        $brand = Brand::all();
        $viewData = [
            'blog' => $blog,
            'slide' => $slide,
            'blo' => $blo,
            'pro' => $pro,
            'brand'=> $brand
        ];
        return view('front.blog', $viewData);
    }

    public function getRegister()
    {
        return view('front.layouts.register');
    }
    
    public function getLogin()
    {
        return view('front.layouts.login');
    }

    public function getSearch(Request $request)
    {
        $category = Category::where('name_cate', 'like', '%'.$request->input_search.'%')->first();
        
            if($category) {
                $product = Product::where('category_id', '=', $category->id)->get();
            } else {
                $product = Product::where('name_pr', 'like', '%'.$request->input_search.'%')->get();
            }
        $slide = Slide::all();
        $sale = Product::where('discount', '>', 0)->limit(4)->get();
        $status = Product::where('status', 1)->limit(4)->get();
        $pro = Product::all();
        $brand = Brand::all();
        $blog = Blog::all();
        $viewData = [
            'product' => $product,
            'slide' => $slide,
            'sale' => $sale,
            'status' => $status,
            'pro' => $pro,
            'blog' => $blog,
            'brand'=> $brand
        ];
        return view('front.search', $viewData);
    }

    public function postCheckout(Request $request)
    {
        // dd($request);
        $cart = Session::get('Cart');
        $order_detail = Order_Detail::all();
        
        $order = new Order;
        $order->customer_id = Auth::user()->customer->id;
        $order->staff_id =  1;
        $order->total_money = $cart->totalPrice;
        $order->created_date = \Carbon\Carbon::now();
        $order->save();
  
        foreach ($cart->products as $key => $value) {
            $orderdetail = new Order_Detail;
            $orderdetail->order_id = $order->id;
            $orderdetail->product_id = $key;
            $orderdetail->price = $value['price'] / $value['quanty'];
            $orderdetail->quanty = $value['quanty'];
            $orderdetail->save();
  
            $product = Product::find($key);
            $product->quantity -= $value['quanty'];
            $product->sold += $value['quanty'];
            $product->save();
        }
        $to_name="Đồ gia dụng";
        $to_mail=$request['email'];
        $title_mail = "Đồ gia dụng cảm ơn quý khách";
        Mail::send('front.checkoutEmail', compact('order', 'order_detail'), function($message) use ($to_name,$to_mail,$title_mail ){
        $message->to($to_mail)->subject($title_mail );
        $message->from($to_mail, $to_name,$title_mail );
        });
        $request->session()->forget('Cart');
        return redirect()->route('index.getSuccess');
        
    }

    public function getCheckout()
    {
      $pro = Product::all();
      $infor = Customer::all();
      $brand = Brand::all();
      return view('front.checkout', compact('infor', 'pro','brand'));
    }

    public function getSuccess()
    {
        $pro = Product::all();
        $blog = Blog::all();
        $slide = Slide::all();
        $brand = Brand::all();
        $viewData = [
            'blog' => $blog,
            'slide' => $slide,
            'pro' => $pro,
            'brand'=> $brand
        ];
        return view('front.notify', $viewData);
    }

}