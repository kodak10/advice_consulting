<!DOCTYPE html>
<html lang="fr" dir="ltr" data-bs-theme="dark" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="shortcut icon" type="image/png" href="{{ asset('adminAssets/images/logos/favicon.png') }}">

  <link rel="stylesheet" href="{{ asset('adminAssets/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  <title>Advice Consulting | Proforma - Factures</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

  @stack('styles')
  
</head>

<body>
  
  <!-- Preloader -->
  <div class="preloader">
      <img src="{{ asset('assets/images/logo.png') }}" alt="loader" class="lds-ripple img-fluid">
  </div>
  
  @include('frontend.layouts.header')
  
  @include('frontend.layouts.sidebar-mobile')
  

  <div class="main-wrapper overflow-hidden">
      @yield('content')
  </div>

  @include('frontend.layouts.footer')
  

  <!-- Scroll Top -->
  <a href="javascript:void(0)" class="top-btn btn btn-primary d-flex align-items-center justify-content-center round-54 p-0 rounded-circle">
    <i class="ti ti-arrow-up fs-7"></i>
  </a>

  <script src="{{ asset('adminAssets/js/vendor.min.js') }}"></script>

  <script src="{{ asset('adminAssets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('adminAssets/libs/simplebar/dist/simplebar.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/app.dark.init.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/theme.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/app.min.js') }}"></script>

 
  <script src="{{ asset('adminAssets/js/frontend-landingpage/homepage.js') }}"></script>
</body>

</html>