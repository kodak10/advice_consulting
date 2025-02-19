<!DOCTYPE html>
<html lang="fr" dir="ltr" data-bs-theme="dark" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  <!-- Required meta tags -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Favicon icon-->
  <link rel="shortcut icon" type="image/png" href="{{ asset('adminAssets/images/logos/favicon.png') }}">

  <!-- Core Css -->
  <link rel="stylesheet" href="{{ asset('adminAssets/css/styles.css') }}">

  <title>Advice Consulting | Devis - Factures</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <link rel="stylesheet" href="{{ asset('adminAssets/libs/quill/dist/quill.snow.css') }}">
  <link rel="stylesheet" href="{{ asset('adminAssets/libs/dropzone/dist/min/dropzone.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminAssets/libs/select2/dist/css/select2.min.css') }}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

  <link rel="stylesheet" href="{{ asset('adminAssets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">

  <link rel="stylesheet" href="{{ asset('adminAssets/libs/sweetalert2/dist/sweetalert2.min.css') }}">

  @stack('styles')

  <script>
    window.Echo.channel('devis.notifications')
    .listen('.devis.created', (event) => {
        // Exemple : Ajouter une notification à la liste de notifications
        let notificationList = document.getElementById('notification-list');
        let notificationItem = document.createElement('li');
        notificationItem.classList.add('dropdown-item');
        notificationItem.innerText = `Nouveau devis créé : ${event.devis_num}`;
        notificationList.appendChild(notificationItem);

        // Mettez à jour l'icône de notification
        let notificationIcon = document.querySelector('.nav-icon .notification');
        notificationIcon.classList.add('bg-primary');
    });
  </script>
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


      {{-- <aside class="left-sidebar with-horizontal">
        <!-- Sidebar scroll-->
        <div>
          <!-- Sidebar navigation-->
          <nav id="sidebarnavh" class="sidebar-nav scroll-sidebar container-fluid">
            <ul id="sidebarnav">
              <!-- ============================= -->
              <!-- Home -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Home</span>
              </li>
              <!-- =================== -->
              <!-- Dashboard -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span>
                    <i class="ti ti-home-2"></i>
                  </span>
                  <span class="hide-menu">Dashboard</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="index.html" class="sidebar-link">
                      <i class="ti ti-aperture"></i>
                      <span class="hide-menu">Modern</span>
                    </a>
                  </li>
                  
                </ul>
              </li>
              <!-- ============================= -->
              <!-- Apps -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Apps</span>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link two-column has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span>
                    <i class="ti ti-archive"></i>
                  </span>
                  <span class="hide-menu">Apps</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="app-calendar.html" class="sidebar-link">
                      <i class="ti ti-calendar"></i>
                      <span class="hide-menu">Calendar</span>
                    </a>
                  </li>
                  
                  <li class="sidebar-item">
                    <a href="app-chat.html" class="sidebar-link">
                      <i class="ti ti-message-dots"></i>
                      <span class="hide-menu">Chat</span>
                    </a>
                  </li>
                 
                  <li class="sidebar-item">
                    <a href="app-contact.html" class="sidebar-link">
                      <i class="ti ti-phone"></i>
                      <span class="hide-menu">Contact Table</span>
                    </a>
                  </li>
                  
                  <li class="sidebar-item">
                    <a href="app-invoice.html" class="sidebar-link">
                      <i class="ti ti-file-text"></i>
                      <span class="hide-menu">Invoice</span>
                    </a>
                  </li>
                  
                  <li class="sidebar-item">
                    <a href="blog-posts.html" class="sidebar-link">
                      <i class="ti ti-article"></i>
                      <span class="hide-menu">Posts</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="blog-detail.html" class="sidebar-link">
                      <i class="ti ti-details"></i>
                      <span class="hide-menu">Detail</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="eco-shop.html" class="sidebar-link">
                      <i class="ti ti-shopping-cart"></i>
                      <span class="hide-menu">Shop</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="eco-shop-detail.html" class="sidebar-link">
                      <i class="ti ti-basket"></i>
                      <span class="hide-menu">Shop Detail</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="eco-product-list.html" class="sidebar-link">
                      <i class="ti ti-list-check"></i>
                      <span class="hide-menu">List</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="eco-checkout.html" class="sidebar-link">
                      <i class="ti ti-brand-shopee"></i>
                      <span class="hide-menu">Checkout</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link" href="eco-add-product.html">
                      <i class="ti ti-file-plus"></i>
                      <span class="hide-menu">Add Product</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a class="sidebar-link" href="eco-edit-product.html">
                      <i class="ti ti-file-pencil"></i>
                      <span class="hide-menu">Edit Product</span>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- ============================= -->
              <!-- Frontend pages -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Frontend pages</span>
              </li>
              <!-- =================== -->
              <!-- pages -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <i class="ti ti-app-window"></i>
                  </span>
                  <span class="hide-menu">Frontend pages</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="frontend-landingpage.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Homepage</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="frontend-aboutpage.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">About Us</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="frontend-contactpage.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Contact Us</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="frontend-blogpage.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Blog</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="frontend-blogdetailpage.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Blog Details</span>
                    </a>
                  </li>
                </ul>
              </li>

              <!-- ============================= -->
              <!-- PAGES -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">PAGES</span>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span>
                    <i class="ti ti-notebook"></i>
                  </span>
                  <span class="hide-menu">Pages</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="page-faq.html" class="sidebar-link">
                      <i class="ti ti-help"></i>
                      <span class="hide-menu">FAQ</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="page-account-settings.html" class="sidebar-link">
                      <i class="ti ti-user-circle"></i>
                      <span class="hide-menu">Account Setting</span>
                    </a>
                  </li>
                 
                  <li class="sidebar-item">
                    <a href="widgets-cards.html" class="sidebar-link">
                      <i class="ti ti-cards"></i>
                      <span class="hide-menu">Card</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="widgets-banners.html" class="sidebar-link">
                      <i class="ti ti-ad"></i>
                      <span class="hide-menu">Banner</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="widgets-charts.html" class="sidebar-link">
                      <i class="ti ti-chart-bar"></i>
                      <span class="hide-menu">Charts</span>
                    </a>
                  </li>
                  
                </ul>
              </li>
              <!-- ============================= -->
              <!-- UI -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">UI</span>
              </li>
              <!-- =================== -->
              <!-- UI Elements -->
              <!-- =================== -->
              <li class="sidebar-item mega-dropdown">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <i class="ti ti-layout-grid"></i>
                  </span>
                  <span class="hide-menu">UI</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  
                  
                  <li class="sidebar-item">
                    <a href="ui-buttons.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Buttons</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="ui-dropdowns.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Dropdowns</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="ui-modals.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Modals</span>
                    </a>
                  </li>
                 
                  <li class="sidebar-item">
                    <a href="ui-tooltip-popover.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Tooltip & Popover</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="ui-notification.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Alerts</span>
                    </a>
                  </li>
                  
                  <li class="sidebar-item">
                    <a href="ui-pagination.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Pagination</span>
                    </a>
                  </li>
                  
                 
                  <li class="sidebar-item">
                    <a href="ui-breadcrumb.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Breadcrumb</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="ui-offcanvas.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Offcanvas</span>
                    </a>
                  </li>
                 
                  
                  
                  
                  <li class="sidebar-item">
                    <a href="ui-spinner.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Spinner</span>
                    </a>
                  </li>
                  
                </ul>
              </li>
              <!-- ============================= -->
              <!-- Forms -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Forms</span>
              </li>
              <!-- =================== -->
              <!-- Forms -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link two-column has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <i class="ti ti-file-text"></i>
                  </span>
                  <span class="hide-menu">Forms</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <!-- form elements -->
                  <li class="sidebar-item">
                    <a href="form-inputs.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Forms Input</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="form-input-groups.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Input Groups</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="form-input-grid.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Input Grid</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="form-checkbox-radio.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Checkbox & Radios</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="form-bootstrap-switch.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Bootstrap Switch</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="form-select2.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Select2</span>
                    </a>
                  </li>
                  <!-- form inputs -->
                 
                  <li class="sidebar-item">
                    <a href="form-vertical.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Form Vertical</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="form-horizontal.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Form Horizontal</span>
                    </a>
                  </li>
                  
                  <li class="sidebar-item">
                    <a href="form-row-separator.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Row Separator</span>
                    </a>
                  </li>
                  
                  <li class="sidebar-item">
                    <a href="form-detail.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Form Detail</span>
                    </a>
                  </li>
                  <!-- form wizard -->
                  <li class="sidebar-item">
                    <a href="form-wizard.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Form Wizard</span>
                    </a>
                  </li>
                  <!-- Quill Editor -->
                  <li class="sidebar-item">
                    <a href="form-editor-quill.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Quill Editor</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- ============================= -->
              <!-- Tables -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Tables</span>
              </li>
              <!-- =================== -->
              <!-- Bootstrap Table -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <i class="ti ti-layout-sidebar"></i>
                  </span>
                  <span class="hide-menu">Tables</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="table-basic.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Basic Table</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="table-dark-basic.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Dark Basic Table</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="table-sizing.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Sizing Table</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="table-layout-coloured.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Coloured Table</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="table-datatable-basic.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Basic Initialisation</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="table-datatable-api.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">API</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="table-datatable-advanced.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Advanced Initialisation</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- ============================= -->
              <!-- Charts -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Charts</span>
              </li>
              <!-- =================== -->
              <!-- Apex Chart -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <i class="ti ti-chart-pie"></i>
                  </span>
                  <span class="hide-menu">Charts</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="chart-apex-line.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Line Chart</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="chart-apex-area.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Area Chart</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="chart-apex-bar.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Bar Chart</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="chart-apex-pie.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Pie Chart</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="chart-apex-radial.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Radial Chart</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="chart-apex-radar.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Radar Chart</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- ============================= -->
              <!-- Icons -->
              <!-- ============================= -->
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Icons</span>
              </li>
              <!-- =================== -->
              <!-- Tabler Icon -->
              <!-- =================== -->
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <i class="ti ti-archive"></i>
                  </span>
                  <span class="hide-menu">Icon</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="icon-tabler.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Tabler Icon</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="icon-solar.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Solar Icon</span>
                    </a>
                  </li>
                </ul>
              </li>
              <!-- multi level -->
              <li class="sidebar-item">
                <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                  <span class="rounded-3">
                    <iconify-icon icon="solar:airbuds-case-minimalistic-line-duotone" class="ti"></iconify-icon>
                  </span>
                  <span class="hide-menu">Multi DD</span>
                </a>
                <ul aria-expanded="false" class="collapse first-level">
                  <li class="sidebar-item">
                    <a href="../docs/index.html" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Documentation</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="javascript:void(0)" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Page 1</span>
                    </a>
                  </li>
                  <li class="sidebar-item">
                    <a href="javascript:void(0)" class="sidebar-link has-arrow">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Page 2</span>
                    </a>
                    <ul aria-expanded="false" class="collapse second-level">
                      <li class="sidebar-item">
                        <a href="javascript:void(0)" class="sidebar-link">
                          <i class="ti ti-circle"></i>
                          <span class="hide-menu">Page 2.1</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="javascript:void(0)" class="sidebar-link">
                          <i class="ti ti-circle"></i>
                          <span class="hide-menu">Page 2.2</span>
                        </a>
                      </li>
                      <li class="sidebar-item">
                        <a href="javascript:void(0)" class="sidebar-link">
                          <i class="ti ti-circle"></i>
                          <span class="hide-menu">Page 2.3</span>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="sidebar-item">
                    <a href="javascript:void(0)" class="sidebar-link">
                      <i class="ti ti-circle"></i>
                      <span class="hide-menu">Page 3</span>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </nav>
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </aside> --}}

      <div class="body-wrapper">
            @yield('content')
      </div>
<script>
  function handleColorTheme(e) {
    document.documentElement.setAttribute("data-color-theme", e);
  }
</script>
     

     

    <!--  Search Bar -->
    {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-1">
          <div class="modal-header border-bottom">
            <input type="search" class="form-control fs-3" placeholder="Search here" id="search">
            <a href="javascript:void(0)" data-bs-dismiss="modal" class="lh-1">
              <i class="ti ti-x fs-5 ms-3"></i>
            </a>
          </div>
          <div class="modal-body message-body" data-simplebar="">
            <h5 class="mb-0 fs-5 p-1">Quick Page Links</h5>
            <ul class="list mb-0 py-2">
              <li class="p-1 mb-1 bg-hover-light-black">
                <a href="javascript:void(0)">
                  <span class="d-block">Modern</span>
                  <span class="text-muted d-block">/dashboards/dashboard1</span>
                </a>
              </li>
              <li class="p-1 mb-1 bg-hover-light-black">
                <a href="javascript:void(0)">
                  <span class="d-block">Dashboard</span>
                  <span class="text-muted d-block">/dashboards/dashboard2</span>
                </a>
              </li>
             
            </ul>
          </div>
        </div>
      </div>
    </div> --}}
   

   


  </div>
  <div class="dark-transparent sidebartoggler"></div>

  <script src="{{ asset('adminAssets/js/vendor.min.js') }}"></script>

  <!-- Import Js Files -->
  <script src="{{ asset('adminAssets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('adminAssets/libs/simplebar/dist/simplebar.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/app.dark.init.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/theme.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/app.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/theme/sidebarmenu.js') }}"></script>

  <!-- solar icons -->
  <script src="{{ asset('adminAssets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/dashboards/dashboard2.js') }}"></script>

  <!-- Import Js Files -->

  <script src="https://cdn.jsdelivr.net/npm/jquery.repeater@1.2.1/jquery.repeater.min.js"></script>
  
  
  <script src="{{ asset('adminAssets/libs/dropzone/dist/min/dropzone.min.css') }}"></script>
  {{-- <script src="{{ asset('adminAssets/libs/select2/dist/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('adminAssets/libs/select2/dist/js/select2.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/forms/select2.init.js') }}"></script> --}}
  {{-- <script src="{{ asset('adminAssets/libs/jquery.repeater/jquery.repeater.min.js') }}"></script> --}}
  <script src="{{ asset('adminAssets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>
  {{-- <script src="{{ asset('adminAssets/js/forms/repeater-init.js') }}"></script> --}}

  <link rel="stylesheet" href="{{ asset('adminAssets/libs/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

  <script src="{{ asset('adminAssets/js/extra-libs/moment/moment.min.js') }}"></script>
  <script src="{{ asset('adminAssets/libs/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/forms/daterangepicker-init.js') }}"></script>
  <script src="{{ asset('adminAssets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/datatable/datatable-basic.init.js') }}"></script>

  <script src="{{ asset('adminAssets/libs/sweetalert2/dist/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('adminAssets/js/forms/sweet-alert.init.js') }}"></script>
  <script src="{{ asset('adminAssets/js/plugins/toastr-init.js') }}"></script>
  @stack('scripts') <!-- C'est ici que les scripts seront inclus -->

</body>

</html>