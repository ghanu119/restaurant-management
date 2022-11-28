<?php

// namespace App\Http\Controllers\Admin;
namespace App\Http\Controllers\Admin;

use App\Helper\UploadFile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateColorRequest;
use App\Models\Color;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class OrderController extends Controller
{

    use UploadFile;

    /**
     * @name index
     *
     */
    public function index ( ) {
        return view('admin.order.view');
    }

    /**
     * @name ajaxTableData
     *
     * Return datatable ajax data
     */
    public function ajaxTableData ( Request $request ){
        $orderColumns = [
            'id',
            'day_wise_id',
            '',
            '',
            'total',
            'created_at',
            'status',
            'created_at',
        ];
        // $orderColumns = Order::DATATABLE_ORDER_COLUMN;
        $totalRecords = Order::count();
        $orders = Order::with(['customer', 'table'])
                    ->withCount('orderItems')
                    ->addSelect( DB::raw('DATE(created_at) as order_date'));

        if( $request->has('search') ){
            $search = $request->get('search');
            if( !empty( $search['value'] )){

                $orders = $orders->allColumnFilter( $search['value'] );
            }
        }

        $fileteredRecords = $orders->count();

        if( $request->has('order') ){
            $order = $request->get('order')[0];
            $orders = $orders->orderBy( $orderColumns[ $order['column'] ], $order['dir'] );
        }
        $orders = $orders->latest()
                        ->take( $request->get('length', 10) )
                        ->skip( $request->get('start', 0))
                        ->get();


        $data = [
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $fileteredRecords,
            'data' => $orders,
        ];
        return response()->json( $data, 200);
    }

    /**
     * @name view
     *
     */
    public function view ( Order $order ) {
        $order->load([
            'customer',
            'orderItems' => function ( $q ) {
                $q->with([
                    'orderProducts',
                    'productCharges'
                ]);
            }
        ]);
        return view('admin.order.data', compact('order'));
    }

    /**
     * @name delete
     *
     * Remove Order complete
     *
     */
    public function delete ( Order $order ) {
        $order->delete();
        $data = [
            'message' => 'Success ! Order has been removed.'
        ];
        return response()->json( $data, 200 );
    }

    /**
     * @name changeStatus
     *
     * Change status of Color
     *
     */
    public function changeStatus ( Order $order ) {
        if( $order->status == 'y' ){
            $order->status = 'n';
        }else{

            $order->status = 'y';
        }
        $order->save();
        $data = [
            'message' => 'Success ! Order status changed successfully.'
        ];
        return response()->json( $data, 200 );
    }

    public function viewManageOrder(){
        return view('admin.order.manage-table');
    }
}
