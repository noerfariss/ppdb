<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PPDB</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500;1,600&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/css/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/css/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet"
        href="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css"
        integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    @routes

    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @inertiaHead
</head>

<body>
    @inertia

    <script src="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/js/helpers.js"></script>
    <script src="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('backend/sneat-1.0.0/') }}/assets/vendor/js/menu.js"></script>
</body>

</html>
