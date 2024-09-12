<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png" loading="lazy" />
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png" loading="lazy" />
    <title>Koperasi - KAMPUS {{ Auth::user()->role }}</title>
    
    <!-- Preload CSS for faster load -->
    <link rel="preload" href="../../assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5" as="style" />
    
    <!-- Fonts and icons -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" as="style" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous" async></script>
    
    <!-- Nucleo Icons -->
    <link href="../../assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
    
    <!-- Preload Popper (if necessary) -->
    <script src="https://unpkg.com/@popperjs/core@2" defer></script>

    <!-- Main Styling -->
    <link href="../../assets/css/soft-ui-dashboard-tailwind.css?v=1.0.5" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet" />

    <!-- Nepcha Analytics (optional) -->
    {{-- <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script> --}}
  </head>

  <body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
    <!-- sidenav -->
    @include('layouts.sidebar')
    <!-- end sidenav -->
    
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
      <!-- Navbar -->
      @include('layouts.header')
      <!-- end Navbar -->

      <!-- cards -->
      <div class="w-full px-6 py-6 mx-auto">
        <!-- row 1 -->
        @yield('content')
        @include('layouts.footer')
      </div>
      <!-- end cards -->
    </main>

    @stack('js')

    <!-- plugin for charts -->
    <script src="../../assets/js/plugins/chartjs.min.js" async></script>
    
    <!-- plugin for scrollbar -->
    <script src="../../assets/js/plugins/perfect-scrollbar.min.js" async></script>
    
    <!-- GitHub button -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    
    <!-- main script file -->
    <script src="../../assets/js/soft-ui-dashboard-tailwind.js?v=1.0.5" async></script>

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" defer></script>

    <!-- Initialize DataTable -->
    <script type="text/javascript" defer>
      document.addEventListener('DOMContentLoaded', function() {
          $('#datatable-basic').DataTable();
      });
    </script>
  </body>
</html>
