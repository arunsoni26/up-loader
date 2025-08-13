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

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{asset('css')}}/toastr.min.css" rel="stylesheet">

    <script src="{{ asset('js/settings.js') }}"></script>
</head>
<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-layout="default">
<div class="wrapper">
