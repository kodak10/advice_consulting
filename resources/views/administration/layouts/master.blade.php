<!DOCTYPE html>
<html lang="fr" dir="ltr" data-bs-theme="dark" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="user-id" content="{{ auth()->id() }}">

  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="{{ asset('adminAssets/images/logos/favicon.png') }}">

  <!-- Core Css -->
  <link rel="stylesheet" href="{{ asset('adminAssets/css/styles.css') }}">

  <title>Advice Consulting | Proforma - Factures</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  
  <link rel="stylesheet" href="{{ asset('adminAssets/libs/quill/dist/quill.snow.css') }}">
  <link rel="stylesheet" href="{{ asset('adminAssets/libs/select2/dist/css/select2.min.css') }}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

  <link rel="stylesheet" href="{{ asset('adminAssets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">

  <link rel="stylesheet" href="{{ asset('adminAssets/libs/sweetalert2/dist/sweetalert2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminAssets/libs/dropzone/dist/min/dropzone.min.css') }}">

  @stack('styles')
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="{{ asset('adminAssets/images/logos/favicon.png') }}" alt="loader" class="lds-ripple img-fluid">
  </div>
  <div id="main-wrapper">
    <!-- Sidebar Start -->
    @include('administration.layouts.aside')
    <!--  Sidebar End -->
    <div class="page-wrapper">
      <!--  Header Start -->
      @include('administration.layouts.header')
      <!--  Header End -->

      <div class="body-wrapper">
        @yield('content')
      </div>
    </div>
  </div>
  <div class="dark-transparent sidebartoggler"></div>

  <!-- Scripts -->
  <script src="{{ asset('adminAssets/js/vendor.min.js') }}"></script>
  <script src="{{ asset('adminAssets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('adminAssets/libs/simplebar/dist/simplebar.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/app.dark.init.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/theme.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/app.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/sidebarmenu.js') }}"></script>

  <script src="https://cdn.jsdelivr.net/npm/jquery.repeater@1.2.1/jquery.repeater.min.js"></script>
  <script src="{{ asset('adminAssets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/extra-libs/moment/moment.min.js') }}"></script>
  <script src="{{ asset('adminAssets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/forms/daterangepicker-init.js') }}"></script>
  <script src="{{ asset('adminAssets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/datatable/datatable-basic.init.js') }}"></script>
  <script src="{{ asset('adminAssets/libs/sweetalert2/dist/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/forms/sweet-alert.init.js') }}"></script>
  <script src="{{ asset('adminAssets/js/plugins/toastr-init.js') }}"></script>

  <!-- Laravel Echo et Pusher -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.2/echo.iife.min.js"></script>
  {{-- <script src="https://js.pusher.com/7.0/pusher.min.js"></script> --}}
  <script src="https://cdn.jsdelivr.net/npm/pusher-js@7.0.3/dist/web/pusher.min.js"></script>


  <script>
    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '5a299c7322c90ce58687',  // Utilisez votre clé Pusher ici
        cluster: 'eu',  // Utilisez votre cluster Pusher ici
        forceTLS: true,
        debug: true,
        reconnect: true,  // Active la reconnexion automatique

    });

    let userId = document.querySelector('meta[name="user-id"]').getAttribute('content');

window.Echo.private(`user.${userId}`)
    .listen('DevisCreated', (event) => {
        console.log('Notification reçue :', event);
        alert(`Nouveau devis créé : ${event.message}`);
    })
    .error((error) => {
        console.log('Erreur avec Pusher:', error);
    });


</script>





  @stack('scripts') <!-- C'est ici que les scripts seront inclus -->
</body>

</html>