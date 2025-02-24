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

  <!-- External Libraries -->
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

  <!-- Laravel Echo et Pusher -->
  <script src="https://cdn.jsdelivr.net/npm/pusher-js@7.0.3/dist/web/pusher.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.2/echo.iife.min.js"></script>

  <!-- Ensuite, vos autres scripts -->
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

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Inclure SweetAlert2 -->

  <script>
    window.Pusher = Pusher;
    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: '5a299c7322c90ce58687',
      cluster: 'eu',
      forceTLS: true,
      debug: true,
      reconnect: true,
    });
  
    // S'abonner au canal privé
    let userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
  
    window.Echo.private(`user.${userId}`)
      .listen('DevisCreated', (event) => {
          console.log('Notification reçue:', event);  // Affiche l'événement dans la console
  
          // Affichage avec SweetAlert2
          Swal.fire({
              title: 'Nouveau devis créé',
              text: event.message,
              icon: 'info',  // Type d'icône pour l'alerte
              confirmButtonText: 'Ok'
          });
  
          // Ajouter à la liste des notifications
          let notificationList = document.getElementById('notification-list');
          let newNotification = document.createElement('li');
          newNotification.classList.add('notification-item');
          newNotification.innerHTML = `
            <div>
              <strong>Nouveau devis créé</strong><br>
              ${event.message}
            </div>
          `;
          notificationList.appendChild(newNotification);
  
          // Mettre à jour le nombre de notifications dans l'icône
          let notificationCount = document.querySelector('.notification');
          let currentCount = parseInt(notificationCount.textContent || '0');
          notificationCount.textContent = currentCount + 1;
      })
      .error((error) => {
          console.log('Erreur avec Pusher:', error);
          Swal.fire({
              title: 'Erreur de connexion',
              text: `Erreur de connexion avec Pusher: ${error.message}`,
              icon: 'error',
              confirmButtonText: 'Ok'
          });
      })
      .subscribe(() => {
          console.log(`Abonnement au canal privé user.${userId} réussi`);
      });
  </script>

  @stack('scripts') <!-- C'est ici que les scripts seront inclus -->
</body>

</html>
