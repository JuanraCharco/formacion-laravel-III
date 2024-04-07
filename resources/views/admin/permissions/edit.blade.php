
@extends('adminlte::page')



@section('content_header')
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                <h4 class="text-bold m-0"><i class="fas fa-fw fa-lock" ></i> Permissions</h4>
            </div>
            <div class="col-lg-4 col-xl-4 d-none d-lg-inline-block">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{ env('APP_NAME') }}</a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active">Permissions</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')

    <div class="row">
        <div class="col-12 mt-4">
            <form action="{{ route("permissions.update", [$permission->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <label for="name">Name*</label>
                <div class="input-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($permission) ? $permission->name : '') }}" required>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                </div>
                <div class="mt-4">
                    <input class="btn btn-danger" type="submit" value="Save">
                    &nbsp;
                    <a href="{{ route('permissions.index') }}" class="btn btn-primary">Cancel</a>
                </div>
            </form>

        </div>
    </div>

@stop

@section('css')



@stop

@section('js')


@stop
