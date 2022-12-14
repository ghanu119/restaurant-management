@extends('admin.layouts.app')

@section('title')
    All Tables
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tables</h1>
        <a href="{{route('admin.table.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create New
        </a>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tables Lists</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table id="tables_table" class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr role="row">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th class="tables_img">Image</th>
                                    <th class="tables_capacity">Capacity</th>
                                    <th class="tables_price">Price</th>
                                    <th class="tables_status">Status</th>
                                    <th class="tables_action">Action</th>
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
    <script src="{{url('assets/admin/js/table.js')}}"></script>

@endsection
