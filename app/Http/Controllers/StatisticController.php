<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function index() {
        $orders = Order::select(DB::raw("COUNT(*) as count"))
                ->whereYear('created_at', date('Y'))
                ->groupBy(DB::raw("Month(created_at)"))
                ->pluck('count');
        $months = Order::select(DB::raw("Month(created_at) as month"))
                ->whereYear('created_at', date('Y'))
                ->groupBy(DB::raw("Month(created_at)"))
                ->pluck('month');
        $data = [0,0,0,0,0,0,0,0,0,0,0,0];
        foreach($months as $index => $months){
            --$months;
            $data[$months] = $orders[$index];
        }
        
        $profit = DB::table('orders')->where('status','=',3)->sum('total_money');
        $sold = DB::table('products')->sum('sold');
        return view('admin.statistic.index', compact('profit','sold',['data']));
    }
    
    public function loadChart() {
        
        $respone = ['success' => true,'data' => ''];
        $order = Order::orderBy('created_at', 'desc')->take(20)->get();
        foreach ($order as $item) {
            $respone['times'][] = date('d-m-Y', strtotime($item->updated_at));
            $respone['values'][] = $item->total_money;
          
        }
        return response()->json($respone, 200);
    }

    public function loadChart2() {
        $respone = ['success' => true,'data' => ''];
        $sp = Product::select('category_id', DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->pluck('total','category_id')->all();

        foreach($sp as $key => $value) {
            $respone['times'][] = Category::find($key)->name_cate;
            $respone['values'][] = $value;
        }
        return response()->json($respone, 200);
    }

    public function loadChart3() {

        $respone = ['success' => true,'data' => ''];
        $sp = Product::select('brand_id', DB::raw('count(*) as total'))
            ->groupBy('brand_id')
            ->pluck('total','brand_id')->all();

        foreach($sp as $key => $value) {
            $respone['times'][] = Brand::find($key)->name_bra;
            $respone['values'][] = $value;
        }
        return response()->json($respone, 200);
    }
}
