@extends('admin.layouts.app')

@section('title')
    All Orders
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Orders</h1>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Orders Lists</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table id="order_table" class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr role="row">
                                    <th>#</th>
                                    <th>Order Id</th>
                                    <th>Table Name</th>
                                    <th class="table_status">Table Status</th>
                                    <th>Customer Name</th>
                                    <th>Total Orders</th>
                                    <th class="order_price">Price</th>
                                    <th>Order Date</th>
                                    <th class="order_status">Status</th>
                                    <th class="order_action">Action</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js-footer')
    <script src="{{url('assets/admin/js/order.js')}}"></script>

@endsection
