<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Order_Detail;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class CheckOutController extends Controller
{
  public function index()
  {
      $order = Order::orderBy('id', 'desc')->get();
      $viewData = [
          'order' => $order,
      ];
      return view('admin.order.list_order', $viewData);
  }

  public function acceptOrder($id) 
  {
      $order = Order::find($id);
      $order->status = 1;
      $order->update();
      return redirect()->back()->with('success', 'Xác nhận đơn hàng DH'.$order->id.' thành công!');
  }

  public function startShip($id) 
  {
      $order = Order::find($id);
      $order->status = 2;
      $order->update();
      return redirect()->back()->with('success', 'Đơn hàng DH'.$order->id.' đã bắt đầu được giao!');
  }

  public function acceptPayment($id) 
  {
      $order = Order::find($id);
      $order->status = 3;
      $order->update();
      return redirect()->back()->with('success', 'Đơn hàng DH'.$order->id.' đã được thanh toán!');
  }

  public function cancelOrder($id) 
  { 
      $order = Order::find($id);
      if($order->status == 0 && $order->customer_id == Auth::user()->customer->id ) {
          $order->status = -1;
          $order->update();

          foreach ($order->order_detail as $item) {
            $product = Product::find($item->product_id);
            $product->quantity += $item->quanty;
            $product->sold -= $item->quanty;
            $product->update();
        }

          return redirect()->back()->with('success', 'Đơn hàng DH'.$order->id.' đã được huỷ!');
      } else if($order->status == -2) {
          $order->status = -1;
          $order->update();
          foreach ($order->order_detail as $item) {
            $product = Product::find($item->product_id);
            $product->quantity += $item->quanty;
            $product->sold -= $item->quanty;
            $product->update();
        }
          return redirect()->back()->with('success', 'Đơn hàng DH'.$order->id.' đã được huỷ!');
      }

      return redirect()->back()->with('loi', 'Lỗi huỷ đơn hàng!');
  }

  public function cancelShip($id) 
  {
      $order = Order::find($id);
      $order->status = -2;
      $order->update();
      return redirect()->back()->with('success', 'Đơn hàng DH'.$order->id.' giao hàng không thành công, chờ giao lại!');
  }

  public function getView($id)
  {
      $order = Order::find($id);
      $respone = array('data' => $order);

      $customer = $order->customer;
      $respone['data']['customer'] = $customer;

      foreach ($order->order_detail as $item) {
          $respone['data']['order_detail'] = $item;
          if($item->product) {
              foreach ($item->product as $item2) {
              }
          } else {
              $respone['data']['order_detail']['product'] = "Không tồn tại";
          }
      }
      return $respone;
  }

  public function print($id)
  {
      $order = Order::find($id);
      $order_detail = Order_Detail::where('order_id', $id)->get();

      $pdf = PDF::loadView('admin.order.bill_print', compact('order', 'order_detail'));

      $title = 'HD'.$id.'-ngay-xuat-'.$order->created_date.'.pdf';
    return $pdf->stream($title);
  }
 
}