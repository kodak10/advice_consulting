<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="user-id" content="{{ auth()->id() }}">

  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="{{ asset('adminAssets/images/logos/favicon.png') }}">

  <link rel="stylesheet" href="{{ asset('adminAssets/css/styles.css') }}">

  <title>Advice Consulting | Proforma - Factures</title>

  <link rel="stylesheet" href="{{ asset('adminAssets/libs/select2/dist/css/select2.min.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link rel="stylesheet" href="{{ asset('adminAssets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminAssets/libs/sweetalert2/dist/sweetalert2.min.css') }}">
  
  <link rel="stylesheet" href="{{ asset('adminAssets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

  @stack('styles')
</head>

<body>

    <div class="preloader">
        <img src="{{ asset('adminAssets/images/logos/favicon.png') }}" alt="loader" class="lds-ripple img-fluid">
    </div>

    <div id="main-wrapper">

        @include('administration.layouts.aside')

        <div class="page-wrapper">
            @include('administration.layouts.header')

            <div class="body-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    <div class="dark-transparent sidebartoggler"></div>


    <script src="{{ asset('adminAssets/js/vendor.min.js') }}"></script>


    <script src="{{ asset('adminAssets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminAssets/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('adminAssets/js/theme/app.dark.init.js') }}"></script>
    <script src="{{ asset('adminAssets/js/theme/theme.js') }}"></script>
    <script src="{{ asset('adminAssets/js/theme/app.min.js') }}"></script>
    <script src="{{ asset('adminAssets/js/theme/sidebarmenu.js') }}"></script>

   

    <script src="{{ asset('adminAssets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminAssets/js/datatable/datatable-basic.init.js') }}"></script>

    {{-- <script src="{{ asset('adminAssets/libs/sweetalert2/dist/sweetalert2.min.js') }}"></script> --}}
    <script src="{{ asset('adminAssets/js/forms/sweet-alert.init.js') }}"></script>
    <script src="{{ asset('adminAssets/js/plugins/toastr-init.js') }}"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('adminAssets/js/extra-libs/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminAssets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('adminAssets/js/forms/daterangepicker-init.js') }}"></script>
    
    <!-- Laravel Echo et Pusher -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.2/echo.iife.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@7.0.3/dist/web/pusher.min.js"></script>


    <script>
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '5a299c7322c90ce58687',  // clé Pusher
            cluster: 'eu',  
            forceTLS: true,
            debug: true,
            reconnect: true,  // Active la reconnexion automatique
        });

        let userId = document.querySelector('meta[name="user-id"]').getAttribute('content');

        if (userId) {
            let channel = window.Echo.private(`user.${userId}`);

            channel.listen('.devis.created', (event) => {
                console.log('Notification reçue :', event);

                // Affichage avec Toast
                toastr.info(`Devis numéro : ${event.devis_number}`, 'Nouveau devis créé', {
                    positionClass: 'toast-top-right',  
                    timeOut: 5000,  
                    closeButton: true,  
                    progressBar: true,
                });

                // Ajouter à la liste des notifications
                let notificationList = document.getElementById('notification-list');
                let newNotification = document.createElement('li');
                newNotification.classList.add('notification-item');
                newNotification.innerHTML = `
                    <div>
                        <strong>Nouveau devis créé</strong><br>
                        Devis numéro : ${event.devis_number}
                    </div>
                `;
                notificationList.appendChild(newNotification);

                let notificationCount = document.querySelector('.notification');
                let currentCount = parseInt(notificationCount.textContent || '0');
                notificationCount.textContent = currentCount + 1;
            });

            channel.error((error) => {
                console.log('Erreur avec Pusher:', error);
                toastr.error(`Erreur de connexion avec Pusher: ${error.message}`, 'Erreur', {
                    positionClass: 'toast-top-right',
                    timeOut: 5000,
                    closeButton: true,
                    progressBar: true,
                });
            });

        } else {
            console.error('❌ Erreur : Impossible de récupérer userId.');
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Action "Tout marquer comme lu"
            document.getElementById("mark-all-as-read").addEventListener("click", function () {
                fetch("{{ route('dashboard.notifications.mark-all-as-read') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                        "Content-Type": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Supprime toutes les notifications et cache le badge
                        document.querySelectorAll(".notification-item").forEach(item => item.remove());
                        document.getElementById("notification-count").style.display = "none";
                    } else {
                        console.error("Erreur lors de la mise à jour des notifications");
                    }
                })
                .catch(error => console.error("Erreur:", error));
            });

            // Action "Masquer individuellement"
            document.querySelectorAll(".notification-item").forEach(item => {
                item.addEventListener("click", function () {
                    let notificationId = this.getAttribute("data-id");

                    fetch(`{{ route('dashboard.notifications.mark-as-read', ':id') }}`.replace(':id', notificationId), {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                            "Content-Type": "application/json"
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Supprime la notification cliquée et met à jour le badge
                            this.remove();
                            updateNotificationCount();
                        } else {
                            console.error("Erreur lors de la mise à jour de la notification");
                        }
                    })
                    .catch(error => console.error("Erreur:", error));
                });
            });
        });

    </script>

    @stack('scripts') 


</body>


</html>
