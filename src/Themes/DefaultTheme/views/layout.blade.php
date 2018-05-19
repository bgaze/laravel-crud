@section('html')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @section('metas')
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        @show
        
        <title>@yield('title')</title>
        
        @yield('stylesheets')
    </head>
    
    <body @yield('body-attr')>
        @section('body')
            @yield('content')
        @show
        
        @yield('javascripts')
    </body>
</html>
@show