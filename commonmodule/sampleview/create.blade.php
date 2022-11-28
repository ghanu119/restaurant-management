@extends('admin.layouts.app')

@section('title')
    {{ !empty( ${MODEL_ROUTE_VAR}->name ) ? ${MODEL_ROUTE_VAR}->name : 'Create {MODULE_NAME}'}}
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ !empty( ${MODEL_ROUTE_VAR}->name ) ? ${MODEL_ROUTE_VAR}->name : 'Create {MODULE_NAME}'}}</h1>

    </div>
    @include('component.error-message')
    
    <div class="row">
        
        <div class="col-lg-12">
            
            <form action="{{route('admin.{MODULE_ROUTE_NAME}.post_data')}}" method="POST">
                @csrf
                @if( ${MODEL_ROUTE_VAR}->id )
                    <input type="hidden" name="id" value="{{ ${MODEL_ROUTE_VAR}->id }}">
                @endif
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ !empty( ${MODEL_ROUTE_VAR}->id  ) ? 'Edit {MODULE_NAME}' :'Create {MODULE_NAME}'}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="form-group">
                                    <label>
                                        {MODULE_NAME} Name
                                    </label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter title" value="{{old('name', ${MODEL_ROUTE_VAR}->name)}}">
                                    @error('name')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="col-md-8 pl-0">
        
                                        <label for="{MODULE_LOWER_CASE}Image">
                                            {MODULE_NAME} Image
                                        </label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" data-image-preview-container="#coverImagePreview" data-image-input="{MODULE_LOWER_CASE}_image" id="{MODULE_LOWER_CASE}Image">
                                                
                                                <label class="custom-file-label" for="{MODULE_LOWER_CASE}Image">
                                                    Select {MODULE_NAME} image
                                                </label>
                                            </div>
                                        </div>
                                        @error('{MODULE_LOWER_CASE}_image')
                                            <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mt-2 {{ !empty( old('{MODULE_LOWER_CASE}_image',${MODEL_ROUTE_VAR}->original_image_url) ) ? '': 'd-none'}}" id="coverImagePreview">
                                        
                                        <ul class="mailbox-attachments align-items-stretch  list-unstyled">
                                            <li >
                                                <input type="hidden" name="{MODULE_LOWER_CASE}_image"  value="{{old('{MODULE_LOWER_CASE}_image', ${MODEL_ROUTE_VAR}->image)}}">
                                                <span class="mailbox-attachment-icon has-img h-100">
                                                    <img src="{{ old('{MODULE_LOWER_CASE}_image') && ${MODEL_ROUTE_VAR}->image != old('{MODULE_LOWER_CASE}_image') ? ${MODEL_ROUTE_VAR}->getTempImageUrl( old('{MODULE_LOWER_CASE}_image') ) : ${MODEL_ROUTE_VAR}->original_image_url}}" alt="Attachment" class="h-100 w-100">
                                                </span>
                                    
                                                <div class="mt-3 text-center">
                                                    
                                                    <a href="#" class="btn btn-danger btn-sm del_{MODULE_LOWER_CASE}_img ">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>
                                        Price
                                    </label>
                                    <input type="text" class="form-control" name="price" placeholder="Enter price" value="{{old('price', ${MODEL_ROUTE_VAR}->price)}}">
                                    @error('price')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>
                                        Details
                                    </label>
                                    <textarea name="description" class="form-control" cols="30" rows="5">{{old('description', ${MODEL_ROUTE_VAR}->description)}}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label> Status </label>
                                    <div class="input-group">
        
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" value="y" type="radio" id="active" name="status" {{ old('status', ${MODEL_ROUTE_VAR}->status) == 'y' ? 'checked' : ''}}>
                                            <label for="active" class="custom-control-label">Active</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-2">
                                            <input class="custom-control-input" type="radio" value="n" id="inactive" name="status" {{ old('status', ${MODEL_ROUTE_VAR}->status) == 'n' ? 'checked' : ''}}>
                                            <label for="inactive" class="custom-control-label">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('admin.{MODULE_ROUTE_NAME}')}}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('js-footer')
    
    <script src="{{url('assets/admin/js/{MODULE_ROUTE_NAME}.js')}}"></script>
    
@endsection