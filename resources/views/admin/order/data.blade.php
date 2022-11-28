@extends('admin.layouts.app')

@section('title')
    Order Id #{{$order->id}} - #{{$order->day_wise_id}}
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Order Id #{{$order->id}} - #{{$order->day_wise_id}}</h1>

    </div>
    @include('component.error-message')

    <div class="row">

        <div class="col-lg-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Detail</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">

                            <div class="form-group">
                                <label>
                                    Name
                                </label>
                                <p class="text-muted">
                                    {{$order->customer->name ?? '-'}}
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>
                                    Mobile
                                </label>
                                <p class="text-muted">
                                    {{$order->customer->mobile ?? '-'}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <hr>
        <div class="col-lg-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Detail</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">

                            <div class="form-group">
                                <label>
                                    Unique Order Id
                                </label>
                                <p class="text-muted">
                                    #{{$order->id}}
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>
                                    Order Id
                                </label>
                                <p class="text-muted">
                                    {{$order->day_wise_id ?? '-'}}
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>
                                    Order Amount
                                </label>
                                <p class="text-muted">
                                    &#8377;{{$order->total}}
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>
                                    Order Status
                                </label>
                                <p class="text-muted">
                                    @if ( $order->status == 1 )
                                        <span class="badge badge-warning">Pending</span>
                                    @elseif ( $order->status == 2 )
                                        <span class="badge badge-primary">Preparing</span>
                                    @elseif ( $order->status == 3 )
                                        <span class="badge badge-success">Done</span>

                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>
                                    Order Date
                                </label>
                                <p class="text-muted">
                                    {{$order->created_at->format('Y-m-d H:i:s')}}
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>
                                    Table Name
                                </label>
                                <p class="text-muted">
                                    {{$order->table_name}}
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>
                                    Table Status
                                </label>
                                <p class="text-muted">
                                    {{$order->table->dining_status == 0 ? 'Free' : 'Serving'}}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="col-lg-12">

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-4">Product Name</div>
                        <div class="col-2">Price</div>
                        <div class="col-1">Qty</div>
                        <div class="col-2">Sub Total</div>
                    </div>
                    @foreach( $order->orderItems as $orderItem )
                    <div class="row border border-top-0 border-left-0 border-right-0 pb-3 mt-2" >
                        <div class="col-4">
                            <span class="badge"> {{$orderItem->product_name}}</span>
                            <br>
                            @if ( $orderItem->productCharges && $orderItem->productCharges->count() )
                                @foreach ($orderItem->productCharges as $charges)
                                    <span class="badge badge-secondary mr-2" > {{$charges->name}} x {{$charges->quantity}} </span>
                                @endforeach
                            @endif
                        </div>
                        <div class="col-2">
                            <span class="badge"> {{$orderItem->product_price}}</span>
                        </div>
                        <div class="col-1">
                            <span class="badge"> {{$orderItem->quantity}}</span>
                        </div>
                        <div class="col-2">
                            <span class="badge"> {{$orderItem->sub_total}}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="card-footer">
                    <a href="{{ route('admin.orders')}}" class="btn btn-primary">Back to list</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
