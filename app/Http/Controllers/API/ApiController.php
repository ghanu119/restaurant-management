<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Color;
use App\Models\ExtraCharge;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderFlavour;
use App\Models\OrderItem;
use App\Models\OrderProductExtraCharge;
use App\Models\OrderTopping;
use App\Models\PackOfTopping;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    /**
     * @name getTables
     *
     */
    public function getTables ( ) {
        $table = Table::with('currentOrder.orderItems.productCharges.extraCharge')
                        ->active()
                        ->orderBy('name')
                        ->get();
        // $table->map(function($item, $key){
        //     $item->setAppends(['thumbnail_image_url']);

        //     return $item;
        // });
        return response()->json( $table, 200 );
    }

    /**
     * @name getProducts
     *
     */
    public function getProducts ( ) {
        $products = Category::with(['products' => function( $pQry ) {
                        $pQry->with([
                            'chargesList' => function( $chargeQry ){
                                $chargeQry->with('extraCharge', function ( $cQuery ){
                                    $cQuery->active();
                                })->whereHas('extraCharge', function ( $cQuery ){
                                    $cQuery->active();
                                });
                            }
                        ])->active();
                    }])
                    ->whereHas('products', function( $q ) {
                        $q->active();
                    })
                    ->active()
                    ->orderBy('name')
                    ->get();
        // $products->map(function($item, $key){
        //     $item->setAppends(['thumbnail_image_url']);

        //     return $item;
        // });
        return response()->json( $products, 200 );
    }

    /**
     * @name getCharges
     *
     */
    public function getCharges ( ) {
        $extraCharges = ExtraCharge::active()->orderBy('price')->get();
        // $extraCharges->map(function($item, $key){
        //     $item->setAppends(['thumbnail_image_url']);

        //     return $item;
        // });
        return response()->json( compact('extraCharges') , 200 );
    }


    public function setGuestToken ( Request $request ) {
        $response = [
            'success' => true,
            '__token' => session()->getId()
        ];
        $token = $request->get('__token');
        if( !empty( $token ) ){
            $response['__token'] = $token;
        }

        return response()->json( $response, 200 );
    }


    public function placeOrder ( Request $request ){

        $orderId = $request->get('order_id');
        $tableId = $request->get('table_id');
        $name = $request->get('name');
        $mobile = $request->get('mobile');

        DB::beginTransaction();

        if( empty( $orderId ) ){
            $user = User::where('mobile', $mobile)->first();
            if( is_null( $user ) ){
                $user = new User;
                $user->name = $name;
                $user->mobile = $mobile;
                $user->save();
            }
        }

        try{
            $orderIdForToday = 1;
            $todayOrderId = Order::whereDate('created_at', today() )->latest()->first();
            if( !is_null( $todayOrderId ) && !empty( $todayOrderId->day_wise_id ) ){
                $orderIdForToday = $todayOrderId->day_wise_id + 1;

            }
            if( !empty( $orderId ) ){
                $order = Order::find( $orderId );
            }else{
                $table = Table::find( $tableId );
                $table->dining_status = 1;
                $table->save();

                $order = new Order();
                $order->day_wise_id = $orderIdForToday;
                $order->user_id = $user->id;
                $order->table_id = $tableId;
                $order->table_name = $table->name;
            }
            $order->total = 0;
            $order->status = 1;
            $order->payment_status = 0;
            $order->save();

            $products = $request->get('products');
            if( !empty( $products ) ){
                $productPriceTotal = 0;
                foreach( $products as $pData ){
                    $product = Product::find( data_get( $pData, 'id') );
                    if( !empty( $product ) ){
                        $productExtraPriceTotal = 0;
                        $qty = data_get( $pData, 'qty', 0);
                        if( $qty >= 1 ){

                            $orderItem = new OrderItem();
                            $orderItem->order_id = $order->id;
                            $orderItem->product_id = $product->id;
                            $orderItem->product_name = $product->name;
                            $orderItem->product_price = $product->price;
                            $orderItem->quantity = $qty;
                            $orderItem->sub_total = $product->price;
                            $orderItem->save();
                            $extraCharges = data_get( $pData, 'extraCharges' );

                            if( !empty( $extraCharges ) && is_array( $extraCharges ) ){
                                foreach( $extraCharges as $ec ){
                                    $ecData = $product->chargesList()->where('id', data_get( $ec, 'id' ) )->first();
                                    if( !empty( $ecData ) ){
                                        $ecData->load('extraCharge');
                                        // dd( $ecData );
                                        $ecQty = data_get( $ec, 'qty', 0 );
                                        if( $ecQty >= 1 ){

                                            $orderProductExtraCharge = new OrderProductExtraCharge();
                                            $orderProductExtraCharge->order_item_id = $orderItem->id;
                                            $orderProductExtraCharge->extra_charge_id = $ecData->id;
                                            $orderProductExtraCharge->name = $ecData->extraCharge->name;
                                            $orderProductExtraCharge->price = $ecData->price;
                                            $orderProductExtraCharge->quantity = $ecQty;
                                            $orderProductExtraCharge->sub_total = $ecData->price * $ecQty;
                                            $orderProductExtraCharge->save();
                                            $productExtraPriceTotal += $orderProductExtraCharge->sub_total;
                                        }
                                    }
                                }
                            }
                            if( $productExtraPriceTotal > 0){
                                $orderItem->sub_total += $productExtraPriceTotal;
                            }
                            $orderItem->sub_total *= $qty;
                            $orderItem->save();
                            $productPriceTotal += $orderItem->sub_total;
                        }
                    }

                }
                if( $productPriceTotal > 0 ){
                    $order->total = $order->orderItems->sum('sub_total');
                    $order->save();
                }
            }
            $order->load(['orderItems.productCharges.extraCharge']);
            return response()->json([
                'success' => true,
                'message' => 'Your order placed successgully.',
                'order_id'=> $orderIdForToday,
                'order_data' => $order
            ]);
        }catch (\Exception $e ){
            \Log::info( $e );
            return response()->json( [
                'success' => false,
                'message' => 'Oops! While getting your order there is an server issue created. Please try again.'
            ], 500);
        }
    }

    public function markTableAsFree ( Request $request ){
        $table = Table::find( $request->get('table_id') );
        if( !is_null( $table ) ){
            $table->dining_status = 0;
            $table->save();
        }
        return response()->json( [ 'success' => true ], 200);
    }

    public function removeOrderItem ( Request $request ){
        $order = null;
        $orderItem = OrderItem::find( $request->get('item_id') );
        if( !is_null( $orderItem ) ){
            $orderItem->delete();
            $order = $orderItem->order;
            if( $order->orderItems->count() > 0 ){
                $order->total = $order->orderItems->sum('sub_total');
                $order->save();

            }else{
                $order->delete();
            }

        }
        return response()->json( [ 'success' => true, 'order_data' => $order ], 200);
    }
}
