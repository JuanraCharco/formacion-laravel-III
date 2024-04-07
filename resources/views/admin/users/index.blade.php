@section('plugins.Datatables', true)
@section('plugins.datatablesPlugins', true)
@section('plugins.toastr', true)
@extends('adminlte::page')



@section('content_header')
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-12 col-sm-12 col-md-12 col-lg-8 col-xl-8">
                <h4 class="text-bold m-0"><i class="fas fa-fw fa-user" ></i> Users</h4>
            </div>
            <div class="col-lg-4 col-xl-4 d-none d-lg-inline-block">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">{{ env('APP_NAME') }}</a></li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover datatable datatable-User">
            <thead>
            <tr class="text-center">
                <th width="350">
                    Name
                </th>
                <th width="300">
                    Correo electrónico
                </th>
                <th>
                    Roles
                </th>
                <th width="110">
                    &nbsp;
                </th>
            </tr>
            </thead>
        </table>
    </div>
@stop

@section('css')


    <style>
        .table td, .table th {
            padding: .3rem;
        }

        .toast-center-center {
            top: 50%;
            left: 50%;
        }

    </style>
@stop

@section('js')

    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/moment-with-locales.js') }}"></script>

    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $(document).ready(function() {

            var dt = $('.datatable-User').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('getUsers') }}",
                dom: '<"row"<"col-lg-4"B><"col-lg-2"l><"col-lg-2"f><"col-lg-4"p><"col-lg-12"rt><"col-lg-6"i><"col-lg-6"p>>',
                columns: [
                    { "data": "name" },
                    { "data": "email" },
                    { "data": "roles" },
                    { "data": 'action', name: 'action', orderable: false, searchable: false}
                ],
                "order": [[0, 'asc']],
                lengthMenu: [ 25, 50, 100, 200, 400 ],
                pageLength: 25,
                language: {
                    url: 'js/datatables/lang/' + document.getElementsByTagName("html")[0].getAttribute("lang") + '.json'
                },
                buttons: [
                    {
                        text: 'Add',
                        className: 'btn-sm btn-success btn-anadir-user text-bold mr-2',
                        action: function ( e, dt, node, config ) {
                            window.location = "users/create";
                        }
                    }
                ],
                "initComplete": function(settings, json) {
                    $(".btn-anadir-user").removeClass("btn-secondary");
                }
            });


        } );
    </script>
@stop
