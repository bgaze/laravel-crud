@section('html')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @yield('metas')
        <title>@yield('title')</title>
        @yield('stylesheets')
    </head>
    <body {!! $body_attr or '' !!}>
        @yield('body')
        @yield('javascripts')
    </body>
</html>
@show