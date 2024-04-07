@extends('adminlte::page')

@section('content')
    <div class="row m-0 p-0">
        <div class="col-12 m-0 p-0">
            <div id="map" class="m-0 p-0" style="width:100%;height: 800px;overflow: hidden;background-color: #fff"></div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/viewer.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/viewer.js') }}"></script>
@stop
