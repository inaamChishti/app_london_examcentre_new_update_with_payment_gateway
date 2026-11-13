<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Dashboard') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <!-- jQuery (CDN) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://fonts.bunny.net/css?family=Nunito:400,500,600,700" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    {{-- style --}}
    @include('layouts.adminDashboard.style')
</head>

<body>
    <div id="app">
        <div class="dashboard-container">

            {{-- side bar start --}}
            @include('layouts.adminDashboard.aside')


            <div class="main-content">

                {{-- side bar start --}}
                @include('layouts.adminDashboard.header')


                <div class="content-wrapper py-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    {{-- js --}}
    @include('layouts.adminDashboard.js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // PAGE LOADING SWEETALERT - SHOW ON EVERY PAGE
    (function () {
        // Show loading modal immediately
        Swal.fire({
            title: 'Please wait…',
            html: `
                <div style="display:flex; align-items:center; gap:12px; margin-top:10px;">
                    <div class="spinner-border text-primary" style="width:2rem; height:2rem;" role="status"></div>
                    <span style="font-size:1rem; font-weight:500;">Loading page, please wait...</span>
                </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Function to close loader
        const closeLoader = () => {
            if (Swal.isVisible()) {
                Swal.close();
            }
        };

        // Close when ALL resources (images, scripts, etc.) are loaded
        window.addEventListener('load', closeLoader);

        // Fallback: Close on DOM ready (in case some assets hang)
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(closeLoader, 300);
        });
    })();
</script>
</body>

</html>
