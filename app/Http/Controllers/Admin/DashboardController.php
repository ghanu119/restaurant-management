<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    /**
     * @name login
     *
     * Show a login page
     */
    public function index () {
        $totalEarnings = Order::sum('total');
        $totalOrders = Order::count();
        $totalTodayOrders = Order::whereDate('created_at', today() )->count();
        $data = [
            'totalEarnings' => $totalEarnings,
            'totalOrders' => $totalOrders,
            'totalTodayOrders' => $totalTodayOrders
        ];
        return view('admin.dashboard', compact( 'data' ) );
    }

    /**
     * @name getLatestNewOrder
     *
     * fetched the latest order of today
     *
     */
    public function fetchTodayOrder (Request $request) {
        $id = $request->get('id');
        $data = [];
        $order = Order::with([
                    'customer',
                    'orderItems' => function ( $q ) {
                        $q->with(['order_flavors','custom_toppings']);
                    }
                ])
                ->where( DB::Raw( 'DATE(created_at)' ), '=', today() )
                ->notCompleted();
        if( !empty( $id ) ){
            $order = $order->where('id', '>', $id);
        }
        $order = $order->limit(30)
                ->get();
        $data = [
            'orders' => $order
        ];
        return response()->json( $data, 200);
    }

    /**
     * @name changeOrderStatus
     *
     * change Order status
     *
     */
    public function changeOrderStatus (Request $request) {
        $id = $request->get('id');
        $status = $request->get('status');
        $data = [];
        $order = Order::find( $id );
        if( !is_null( $order ) ){
            $order->status = $status;
            $order->save();
        }else{
            $status = false;
        }
        $data = [
            'status' => $status
        ];
        return response()->json( $data, 200);
    }
}
