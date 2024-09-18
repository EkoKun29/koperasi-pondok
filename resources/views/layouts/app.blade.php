<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}" loading="lazy" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}" loading="lazy" />
    <title>Koperasi - KAMPUS {{ Auth::user()->role }}</title>
    
    <!-- Preload CSS for faster load -->
    <link rel="preload" href="{{ asset('assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5') }}" as="style" />

    <!-- Fonts and icons -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" as="style" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous" async></script>

    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />

    <!-- Main Styling -->
    <link href="{{ asset('assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5') }}" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/perfect-scrollbar.css') }}">

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" defer></script>
    
    <!-- Nepcha Analytics (optional) -->
    {{-- <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script> --}}
  </head>

  <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
    <!-- sidenav -->
    @include('layouts.sidebar')
    <!-- end sidenav -->

    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
      <!-- cards -->
      <div class="w-full px-6 py-6 mx-auto">
        <!-- row 1 -->
        @yield('content')
        @include('layouts.footer')
      </div>
      <!-- end cards -->
    </main>

    @stack('js')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>

    <!-- Plugin for charts -->
    <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}" async></script>

    <!-- Plugin for scrollbar -->
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>

    <!-- GitHub button -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Main script file -->
    <script src="{{ asset('assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5') }}" async></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('assets/js/perfect-scrollbar.js') }}" defer></script>
    <script src="{{ asset('assets/js/sidenav-burger.js') }}" defer></script>
    <script src="{{ asset('assets/js/navbar-sticky.js') }}" defer></script>

    <!-- Initialize DataTable -->
    <script type="text/javascript" defer>
      document.addEventListener('DOMContentLoaded', function() {
          $('#datatable-basic').DataTable();
      });
    </script>
  </body>
</html>
