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
   public function addCart(Request $request, $id)
    {
        $requestQty = max(1, (int) $request->quantity);

        $product = DB::table('products')->where('id', $id)->first();
        if (!$product || $product->quantity <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sản phẩm không khả dụng'
            ], 400);
        }

        $oldCart = session('Cart');
        $currentQtyInCart = 0;

        if ($oldCart && isset($oldCart->products[$id])) {
            $currentQtyInCart = $oldCart->products[$id]['quanty'];
        }

        $available = $product->quantity - $currentQtyInCart;

        if ($available <= 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sản phẩm đã đạt số lượng tối đa trong giỏ'
            ], 400);
        }

        $addQty = min($requestQty, $available);

        $newCart = new Cart($oldCart);
        $newCart->AddCart($product, $id, $addQty);
        session()->put('Cart', $newCart);

        return response()->json([
            'status' => $addQty < $requestQty ? 'partial' : 'success',
            'added' => $addQty,
            'message' => $addQty < $requestQty
                ? "Đã thêm được {$addQty} sản phẩm do giới hạn tồn kho"
                : 'Đã thêm sản phẩm vào giỏ',
            'html' => view('front.layouts.list_cart')->render()
        ]);
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