<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="dark" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="{{ asset('adminAssets/images/logos/favicon.ico') }}">

  <!-- Core Css -->
  <link rel="stylesheet" href="{{ asset('adminAssets/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

  <title>Advice Consulting | Devis - Factures</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

  <!-- Owl Carousel  -->
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="{{ asset('assets/images/logo.png') }}" alt="loader" class="lds-ripple img-fluid">
  </div>
  <!-- ------------------------------------- -->
  <!-- Top Bar Start -->
  <!-- ------------------------------------- -->
  
  <!-- ------------------------------------- -->
  <!-- Top Bar End -->
  <!-- ------------------------------------- -->

  <!-- ------------------------------------- -->
  <!-- Header Start -->
  <!-- ------------------------------------- -->
  @include('frontend.layouts.header')
  <!-- ------------------------------------- -->
  <!-- Header End -->
  <!-- ------------------------------------- -->

  <!-- ------------------------------------- -->
  <!-- Responsive Sidebar Start -->
  <!-- ------------------------------------- -->
  @include('frontend.layouts.sidebar-mobile')
  <!-- ------------------------------------- -->
  <!-- Responsive Sidebar End -->
  <!-- ------------------------------------- -->

  <div class="main-wrapper overflow-hidden">
   
    @yield('content')
 
  </div>

  <!-- ------------------------------------- -->
  <!-- Footer Start -->
  <!-- ------------------------------------- -->
  <footer>
    <div class="container-fluid">
     
      <div class="d-flex justify-content-between py-7 flex-md-nowrap flex-wrap gap-sm-0 gap-3">
        <div class="d-flex gap-3 align-items-center">
          <img src="{{ asset('adminAssets/images/logos/favicon.png') }}" alt="icon" style="height: 20px">
          <p class="fs-4 mb-0">Tous droits réservés par Advice Consulting. </p>
        </div>
        
      </div>
    </div>
  </footer>
  <!-- ------------------------------------- -->
  <!-- Footer End -->
  <!-- ------------------------------------- -->

  <!-- Scroll Top -->
  <a href="javascript:void(0)" class="top-btn btn btn-primary d-flex align-items-center justify-content-center round-54 p-0 rounded-circle">
    <i class="ti ti-arrow-up fs-7"></i>
  </a>

  <script src="{{ asset('adminAssets/js/vendor.min.js') }}"></script>
  <!-- Import Js Files -->
  <script src="{{ asset('adminAssets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('adminAssets/libs/simplebar/dist/simplebar.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/app.dark.init.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/theme.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/app.min.js') }}"></script>

 
  <script src="{{ asset('adminAssets/js/frontend-landingpage/homepage.js') }}"></script>
</body>

</html>