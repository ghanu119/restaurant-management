@extends('admin.layouts.app')

@section('title')
    {{ !empty( $product->name ) ? $product->name : 'Create Product'}}
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ !empty( $product->name ) ? $product->name : 'Create Product'}}</h1>

    </div>
    @include('component.error-message')

    <div class="row">

        <div class="col-lg-12">

            <form action="{{route('admin.product.post_data')}}" method="POST">
                @csrf
                @if( $product->id )
                    <input type="hidden" name="id" value="{{ $product->id }}">
                @endif
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ !empty( $product->id  ) ? 'Edit Product' :'Create Product'}}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="form-group">
                                    <label>
                                        Product Name
                                    </label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter title" value="{{old('name', $product->name)}}">
                                    @error('name')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="col-md-8 pl-0">

                                        <label for="productsImage">
                                            Product Image
                                        </label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" data-image-preview-container="#coverImagePreview" data-image-input="products_image" id="productsImage">

                                                <label class="custom-file-label" for="productsImage">
                                                    Select Product image
                                                </label>
                                            </div>
                                        </div>
                                        @error('product_image')
                                            <div class="text-danger">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mt-2 {{ !empty( old('products_image',$product->original_image_url) ) ? '': 'd-none'}}" id="coverImagePreview">

                                        <ul class="mailbox-attachments align-items-stretch  list-unstyled">
                                            <li >
                                                <input type="hidden" name="products_image"  value="{{old('products_image', $product->image)}}">
                                                <span class="mailbox-attachment-icon has-img h-100">
                                                    <img src="{{ old('products_image') && $product->image != old('products_image') ? $product->getTempImageUrl( old('products_image') ) : $product->original_image_url}}" alt="Attachment" class="h-100 w-100">
                                                </span>

                                                <div class="mt-3 text-center">

                                                    <a href="#" class="btn btn-danger btn-sm del_products_img ">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>
                                        Select Category
                                    </label>
                                    <select name="category" id="category" class="form-control">
                                        @foreach ($categoryList as $c)
                                        <option value="{{ $c->id }}" {{ $c->id == $product->category_id ? 'selected' : '' }}> {{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>
                                        Select Extra Charges
                                    </label>
                                    <div id="rest-sys">
                                        <admin-product-extra-charge-input :charges-list="{{ $extraChargesList->toJson() }}" :selected-charges="{{ json_encode($product->selected_charges, true) }}"/>
                                    </div>

                                    @if( $errors->has('charges'))
                                        <span class="text-danger">
                                            {{$errors->first('charges')}}
                                        </span>
                                    @endif
                                    @if( $errors->has('charges.*'))
                                        <span class="text-danger">
                                            {{$errors->first('charges.*')}}
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label>
                                        Price
                                    </label>
                                    <input type="text" class="form-control" name="price" placeholder="Enter price" value="{{old('price', $product->price)}}">
                                    @error('price')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>
                                        Details
                                    </label>
                                    <textarea name="description" class="form-control" cols="30" rows="5">{{old('description', $product->description)}}</textarea>
                                    @error('description')
                                        <div class="text-danger">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label> Status </label>
                                    <div class="input-group">

                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" value="y" type="radio" id="active" name="status" {{ old('status', $product->status) == 'y' ? 'checked' : ''}}>
                                            <label for="active" class="custom-control-label">Active</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-2">
                                            <input class="custom-control-input" type="radio" value="n" id="inactive" name="status" {{ old('status', $product->status) == 'n' ? 'checked' : ''}}>
                                            <label for="inactive" class="custom-control-label">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('admin.product')}}" class="btn btn-danger">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('js-header')
<script src="{{url('js/app.js')}}"></script>
@endsection
@section('js-footer')

<script src="{{url('assets/admin/js/product.js')}}"></script>

@endsection
