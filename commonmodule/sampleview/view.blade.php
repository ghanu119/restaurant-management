@extends('admin.layouts.app')

@section('title')
    All {MODULE_NAME}s
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{MODULE_NAME}s</h1>
        <a href="{{route('admin.flavour.create')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create New
        </a>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{MODULE_NAME}s Lists</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table id="{MODEL_TABLE_NAME}_table" class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr role="row">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th class="{MODEL_TABLE_NAME}_img">Image</th>
                                    <th class="{MODEL_TABLE_NAME}_price">Price</th>
                                    <th class="{MODEL_TABLE_NAME}_status">Status</th>
                                    <th class="{MODEL_TABLE_NAME}_action">Action</th>
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
    <script src="{{url('assets/admin/js/{MODULE_ROUTE_NAME}.js')}}"></script>
    
@endsection