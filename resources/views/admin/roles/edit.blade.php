
@extends('adminlte::page')



@section('content_header')
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                <h4 class="text-bold m-0"><i class="fas fa-fw fa-briefcase" ></i> Roles</h4>
            </div>
            <div class="col-lg-4 col-xl-4 d-none d-lg-inline-block">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{ env('APP_NAME') }}</a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')

    <div class="row">
        <div class="col-12 mt-4">
            <form action="{{ route("roles.update", [$role->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <label for="name">Name*</label>
                <div class="input-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($role) ? $role->name : '') }}" required>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                </div>
                <div class="mt-4"></div>
                <div class="form-group {{ $errors->has('permission') ? 'has-error' : '' }}">
                    <label for="permission">Permissions*
                        <span class="btn btn-primary text-xs select-all">Select all</span>
                        <span class="btn btn-primary text-xs deselect-all">Unselect all</span></label>
                    <select name="permission[]" id="permission" class="form-control select2" multiple="multiple" required>
                        @foreach($permissions as $id => $permissions)
                            <option value="{{ $id }}" {{ (in_array($id, old('permissions', [])) || isset($role) && $role->permissions()->pluck('name', 'id')->contains($id)) ? 'selected' : '' }}>{{ $permissions }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('permission'))
                        <em class="invalid-feedback">
                            {{ $errors->first('permission') }}
                        </em>
                    @endif
                </div>
                <div class="mt-4">
                    <input class="btn btn-danger" type="submit" value="Save">
                    &nbsp;
                    <a href="{{ route('roles.index') }}" class="btn btn-primary">Cancel</a>
                </div>
            </form>


        </div>
    </div>

@stop

@section('css')



@stop

@section('js')

    <script>
        $(function () {
            $('.select-all').click(function () {
                let $select2 = $(this).parent().siblings('.select2')
                $select2.find('option').prop('selected', 'selected')
                $select2.trigger('change')
            })
            $('.deselect-all').click(function () {
                let $select2 = $(this).parent().siblings('.select2')
                $select2.find('option').prop('selected', '')
                $select2.trigger('change')
            })

            $('.select2').select2()
        })
    </script>
@stop
