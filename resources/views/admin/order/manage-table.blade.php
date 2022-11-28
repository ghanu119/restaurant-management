@extends('admin.layouts.app')

@section('title')
    Manage Table
@endsection


@section('content')
<div class="container-fluid">
    <div id="rest-sys">
        <admin-manage-table />
    </div>
</div>
@endsection
@section('css-header')
<link rel="stylesheet" href="{{url('css/app.css')}}">
@endsection
@section('js-header')

<script src="{{url('js/app.js')}}"></script>
@endsection
