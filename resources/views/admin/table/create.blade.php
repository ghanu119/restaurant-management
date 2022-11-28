@extends('admin.layouts.app')

@section('title')
    {{ !empty( $table->name ) ? $table->name : 'Create Table'}}
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ !empty( $table->name ) ? $table->name : 'Create Table'}}</h1>

    </div>
    @include('component.error-message')

    <div class="row">

        <div class="col-lg-12">

            <form action="{{route('admin.table.post_data')}}" method="POST">
                @csrf
                @if( $table->id )
                    <input type="hidden" name="id" value="{{ $table->id }}">
                @endif
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ !empty( $table->id  ) ? 'Edit Table' :'Create Table'}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="form-group">
                                    <label>
                                        Name
                                    </label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter title" value="{{old('name', $table->name)}}">
                                    @error('name')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>
                                        Details
                                    </label>
                                    <textarea name="description" class="form-control" cols="30" rows="5">{{old('description', $table->description)}}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>
                                        Capacity
                                    </label>
                                    <input type="number" name="capacity" class="form-control" placeholder="Capacity of table" value={{ $table->capacity }}>
                                    @error('capacity')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label> Status </label>
                                    <div class="input-group">

                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" value="y" type="radio" id="active" name="status" {{ old('status', $table->status) == 'y' ? 'checked' : ''}}>
                                            <label for="active" class="custom-control-label">Active</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-2">
                                            <input class="custom-control-input" type="radio" value="n" id="inactive" name="status" {{ old('status', $table->status) == 'n' ? 'checked' : ''}}>
                                            <label for="inactive" class="custom-control-label">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('admin.table')}}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('js-footer')

    <script src="{{url('assets/admin/js/table.js')}}"></script>

@endsection
