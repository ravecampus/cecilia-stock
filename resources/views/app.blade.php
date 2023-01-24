<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
    <meta name="csrf-token" value="{{ csrf_token() }}"/>
    <meta name="keywords" content="au theme template">
    <title>{{env('MIX_APP_NAME')}}</title>

    <link rel="stylesheet" type="text/css" href="{{ asset(url('/assets/font-awesome/css/font-awesome.min.css')) }}">
    <link href="{{ asset(url('assets/bootstrap/css/bootstrap.min.css')) }}" rel="stylesheet">
    <link href="{{ asset(url('assets/css/app.css')) }}" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet"/>
</head>

<body id="app">

@if (Auth::check())
    @php
    $user_auth_data = [
        'isLoggedin' => true,
        'user' =>  Auth::user(),
    ];
    @endphp
@else
    @php
    $user_auth_data = [
        'isLoggedin' => false
    ];
    @endphp
@endif

<script>
    window.Laravel = JSON.parse(atob('{{ base64_encode(json_encode($user_auth_data)) }}'));
</script>

<script src="{{ asset(url('assets/jquery/jquery.min.js')) }}"></script>
<!-- <script src="{{ asset(url('assets/bootstrap/js/bootstrap.bundle.min.js')) }}"></script> -->
<script src="{{ asset(url('assets/js/app.js')) }}"></script>
<script src="{{ mix('js/app.js') }}" type="text/javascript"></script>


</body>
</html>