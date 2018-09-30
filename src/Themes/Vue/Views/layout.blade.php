@section('html')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @section('metas')
            <meta charset="utf-8">
            <meta name="csrf-token" content="{{ csrf_token() }}">
        @show
        
        <title></title>
                
        @yield('stylesheets')
    </head>
    
    <body>
        @section('body')
        <div id="app">
            <router-view></router-view>
        </div>  
        @show
        
        @section('javascripts')
            <script src="{{ asset('js/app.js') }}"></script>
        @show
    </body>
</html>
@show

