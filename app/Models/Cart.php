<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    public $products = null;
    public $totalPrice = 0;
    public $totalQuanty = 0;
    public function __construct($cart = null)
    {
        if($cart)
        {
            $this->products= $cart->products;
            $this->totalPrice= $cart->totalPrice;
            $this->totalQuanty= $cart->totalQuanty;
        }
    }
    public function AddCart($product, $id, $quantity = 1)
    {
        $unitPrice = $product->discount ? $product->discount : $product->price;

        $newProduct = [
            'quanty' => 0,
            'price' => 0,
            'productInfo' => $product
        ];

        if ($this->products && array_key_exists($id, $this->products)) {
            $newProduct = $this->products[$id];
        }

        // cộng đúng số lượng
        $newProduct['quanty'] += $quantity;
        $newProduct['price'] = $newProduct['quanty'] * $unitPrice;

        $this->products[$id] = $newProduct;

        $this->totalQuanty += $quantity;
        $this->totalPrice += $unitPrice * $quantity;
    }

    public function DeleteItemCart($id){
        $this->totalQuanty -=$this->products[$id]['quanty'];
        $this->totalPrice -= $this ->products[$id]['price'];
        unset($this->products[$id]);
    }

    public function UpdateItemCart($id, $quanty)
    {   
        
        $this->totalQuanty-=$this->products[$id]['quanty'];
        $this->totalPrice-=$this->products[$id]['price'];
        
        $this->products[$id]['quanty']= $quanty;
        $this->products[$id]['price']= $quanty * $this->products[$id]['productInfo']->price;

        $this->totalQuanty+=$this->products[$id]['quanty'];
        $this->totalPrice+=$this->products[$id]['price'];
    }
}
