@extends('adminlte::page')


@section('content_header')
    <div class="container-fluid">
        <div class="row mb-1">
            <div class="col-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
                <h4 class="text-bold m-0"><i class="fas fa-fw fa-folder-open"></i> File manager</h4>
            </div>
            <div class="col-lg-3 col-xl-3 d-none d-lg-inline-block">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">{{ env('APP_NAME') }}</li>
                    <li class="breadcrumb-item active">File manager</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />



    <div class="row">
        <div class="col-12">
            <div id="elfinder"></div>
        </div>
    </div>



@stop

@section('css')
    <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css" />

    <link rel="stylesheet" type="text/css" href="{{ asset('packages/barryvdh/elfinder/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/barryvdh/elfinder/css/theme.css') }}">
@stop

@section('js')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script src="{{ asset('packages/barryvdh/elfinder/js/elfinder.min.js') }}"></script>

        <!-- elFinder translation (OPTIONAL) -->


    <script type="text/javascript" charset="utf-8">
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');



        var FileBrowserDialogue = {
            init: function() {
                // Here goes your code for setting your custom things onLoad.
            },
            mySubmit: function (file) {
                window.parent.postMessage({
                    mceAction: 'fileSelected',
                    data: {
                        file: file
                    }
                }, '*');
            }
        };

        $().ready(function() {

            var h = document.documentElement.clientHeight;

            var elf = $('#elfinder').elfinder({
                height: h - 160,
                resizable: 'no',
                customData: {
                    _token: '{{ csrf_token() }}'
                },
                url: '{{ route("elfinder.connector") }}',  // connector URL
                soundPath: '{{ asset('packages/barryvdh/elfinder/sounds') }}',
                getFileCallback: function(file) { // editor callback
                    FileBrowserDialogue.mySubmit(file); // pass selected file path to TinyMCE
                }
            }).elfinder('instance');
        });


    </script>
@stop



