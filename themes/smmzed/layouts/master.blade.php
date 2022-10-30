<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>@yield('title')</title>
    @yield('seo')


    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/plugins/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/assets/themes/default/vendor/aos/aos.css" rel="stylesheet">
    <link href="/assets/themes/default/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/themes/default/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/themes/default/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/assets/themes/default/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="/assets/themes/default/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    @if (isset($settings['favicon']) && $settings['favicon'])
        <!-- Favicons -->
        <link href="{{$settings['favicon']}}" rel="icon">
        <link href="{{$settings['favicon']}}" rel="apple-touch-icon">
        <link rel="icon" type="image/x-icon" href="{{$settings['favicon']}}">
    @endif

    <!-- Template Main CSS File -->
    <link href="/assets/themes/default/css/style.css" rel="stylesheet">
    @if ($settings['site_css'])
        <style>
            {!! htmlspecialchars_decode($settings['site_css']) !!}
        </style>
    @endif
    @if ($settings['site_head_html'])
        {!! htmlspecialchars_decode($settings['site_head_html']) !!}
    @endif
    @stack('head')
</head>
<body class="{{request()->path()}} {{Illuminate\Support\Str::slug(request()->path())}}">
    @include('sweetalert::alert')

    @include('themes.'.$settings['theme'].'.layouts.header')

    @yield('content')

    @include('themes.'.$settings['theme'].'.layouts.footer')

    @stack('scripts')
    @if ($settings['site_footer_js'])
        <script>
            {!! htmlspecialchars_decode($settings['site_footer_js']) !!}
        </script>
    @endif
</body>
</html>
