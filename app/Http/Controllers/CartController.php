<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index() {
        $pro = Product::all();
        return view('front.cart', compact('pro'));
    }
    public function addCart(Request $request, $id) {
        $product = DB::table('products')->where('id', $id)->first();
        
        if ($product != null) {
            $oldcart = Session('Cart') ? Session('Cart') : null;
            if ((!$oldcart || $oldcart->totalQuanty < $product->quantity )&& $product->quantity >0){
                $newcart = new Cart($oldcart);
                $newcart->addCart($product, $id);
                $request->session()->put('Cart', $newcart);
                return view('front.cart');
            }
            else{
                return false;
            }
        }
    }

    public function getDelete(Request $request, $id) {
        $oldcart = Session('Cart') ? Session('Cart') : null;
        $newcart = new Cart($oldcart);
        $newcart->DeleteItemCart($id);
        
        if (Count($newcart->products) > 0) {
            $request->Session()->put('Cart', $newcart);
        } else {
            $request->Session()->forget('Cart');
        }
        return view('front.layouts.list_cart');
    }

    public function updateCart(Request $request, $id, $quanty) {

        $oldCart = Session('Cart') ? Session('Cart') : null;

        $newCart = new Cart($oldCart);
        $newCart->UpdateItemCart($id, $quanty);
        $request->Session()->put('Cart', $newCart);
        
        return view('front.layouts.list_cart');
       
    }
}