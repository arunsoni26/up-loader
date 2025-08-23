<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="Responsive Admin & Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">

    <link rel="shortcut icon" href="{{ asset('img/icons/icon-48x48.png') }}" />

    <title>@yield('title', config('app.name'))</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link class="js-stylesheet" href="{{ asset('css/light.css') }}" rel="stylesheet">
    
    <link href="{{ asset(path: 'css/light.css') }}" rel="stylesheet">

    <!-- datatable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link href="{{asset('css')}}/toastr.min.css" rel="stylesheet">

    <script src="{{ asset('js/settings.js') }}"></script>
    <style>
        .running-border {
            position: relative;
            border: 3px solid transparent;
            border-radius: 8px;
            padding: 10px;
            background: linear-gradient(white, white) padding-box,
                linear-gradient(90deg, red, orange, yellow, green, blue, purple, red) border-box;
            background-size: 300% 300%;
            animation: borderMove 5s linear infinite;
        }

        @keyframes borderMove {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 300% 50%;
            }
        }

        .zoom-item {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .zoom-item:hover {
            transform: scale(1.02);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
<div class="wrapper">
