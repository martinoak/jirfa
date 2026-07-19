<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>Administrace | JIRFA</title>

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}"/>
    <link rel="apple-touch-icon" type="image/png" href="{{ asset('images/apple-touch-icon.png') }}"/>

    <!-- ===== All CSS files ===== -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('head')
</head>
<body>
    @include('partials.alerts')
    @yield('content')

</body>

@yield('scripts')

</html>
