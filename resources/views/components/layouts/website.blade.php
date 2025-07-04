<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} | KBC HackFest 2025</title>
    <link rel="stylesheet" href="/css/website/LP-Kbc-p1.css">
    <link rel="stylesheet" href="/css/website/header-footer.css">
    <link rel="icon" type="image/png" href="/assets/kbclogo.png"> <!-- favicons tab icon -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    @stack('styles')
    @stack('fonts')
</head>

<body>
    <!-- Header -->
    <x-partials.header/>

    {{ $slot }}

    <!-- Footer -->
    <x-partials.footer/>

    <script src="https://kit.fontawesome.com/baf767acce.js" crossorigin="anonymous"></script> <!--Icons-->
    <script src="/js/website/site.js"></script>
    @stack('scripts')
</body>

</html>
