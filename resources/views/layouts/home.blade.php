<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GestApp</title>

    <!-- Favicons -->
    <link href=" {{ asset('assets/img/logo1.png') }} "rel="icon">
    <link href=" {{ asset('assets/img/logo1.png') }} "rel="apple-touch-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href=" {{ asset('assets/vendor/bootstrap/css/bootstrap.min.css ') }}" rel="stylesheet">
    <link href=" {{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }} " rel="stylesheet">
    <link href=" {{ asset('assets/vendor/boxicons/css/boxicons.min.css') }} " rel="stylesheet">
    <link href=" {{ asset('assets/vendor/quill/quill.snow.css') }} " rel="stylesheet">
    <link href=" {{ asset('assets/vendor/quill/quill.bubble.css') }} " rel="stylesheet">
    <link href=" {{ asset('assets/vendor/remixicon/remixicon.css') }} " rel="stylesheet">
    <link href=" {{ asset('assets/vendor/simple-datatables/style.css') }} " rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href=" {{ asset('assets/css/style.css') }} " rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/form.css') }}"> --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>

    <x-sidebar :services="$services" />
    <x-navbar />
    {{-- @include('components.sidebar'); --}}

    <main id="main" class="main">
        @yield('content')

    </main>
    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>GestApp</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by Yves2110
        </div>
    </footer><!-- End Footer -->




    <!-- Vendor JS Files -->
    <script src=" {{ asset('assets/vendor/apexcharts/apexcharts.min.js') }} "></script>
    <script src=" {{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }} "></script>
    <script src=" {{ asset('assets/vendor/chart.js/chart.min.js') }} "></script>
    <script src=" {{ asset('assets/vendor/echarts/echarts.min.js') }} "></script>
    <script src=" {{ asset('assets/vendor/quill/quill.min.js') }} "></script>
    <script src=" {{ asset('assets/vendor/simple-datatables/simple-datatables.js') }} "></script>
    <script src=" {{ asset('assets/vendor/tinymce/tinymce.min.js') }} "></script>
    <script src=" {{ asset('assets/vendor/php-email-form/validate.js') }} "></script>
    {{-- sweet alert2 cdn --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Template Main JS File -->
    <script src=" {{ asset('assets/js/main.js') }} ">
        // <script src=" {{ asset('assets/js/form.js') }} "></script>
        </body>
        </html>
