<aside class="left-sidebar with-vertical">
    <div><!-- ---------------------------------- -->
      <!-- Start Vertical Layout Sidebar -->
      <!-- ---------------------------------- -->
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard.') }}" class="text-nowrap logo-img">
          <img src="{{ asset('adminAssets/images/logos/logo.png') }}" class="dark-logo" alt="Logo-Dark" style="height: 100px">
          <img src="{{ asset('adminAssets/images/logos/logo.png') }}" class="light-logo" alt="Logo-light" style="height: 100px">
        </a>
        <a href="javascript:void(0)" class="sidebartoggler ms-auto text-decoration-none fs-5 d-block d-xl-none">
          <i class="ti ti-x"></i>
        </a>
      </div>

      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
         
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Accueil</span>
          </li>
          
          <li class="sidebar-item">
            <a class="sidebar-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard.') }}"  aria-expanded="false">
              <span>
                <i class="ti ti-layout-dashboard"></i>
              </span>
              <span class="hide-menu">Menu Principal</span>
            </a>
          </li>

          <!-- ------------------------------- -->
          <!-- Daf - Factures, designation ,client -->
          <!-- ------------------------------- -->

          @if (Auth::user()->hasRole('Daf'))
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Factures</span>
          </li>
          
          <li class="sidebar-item">
            <a class="sidebar-link {{ Request::is('dashboard/factures') ? 'active' : '' }}" href="{{ route('dashboard.factures.index') }}"  aria-expanded="false">
              <span>
                <i class="ti ti-list"></i>
              </span>
              <span class="hide-menu">Les Factures</span>
            </a>
          </li>

         

          @endif



          <!-- ------------------------------- -->
          <!-- Comptable : Proforma, Devis, designation ,client  -->
          <!-- ------------------------------- -->
          @if (Auth::user()->hasRole('Comptable'))
            <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Proforma</span>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link {{ Request::is('dashboard/devis') ? 'active' : '' }}" href="{{ route('dashboard.devis.index') }}"  aria-expanded="false">
                    <span>
                        <i class="ti ti-list"></i>
                    </span>
                    <span class="hide-menu">Mes Proforma</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link {{ Request::is('dashboard/devis/create') ? 'active' : '' }}" href="{{ route('dashboard.devis.create') }}"  aria-expanded="false">
                    <span>
                        <i class="ti ti-receipt"></i> 
                    </span>
                    <span class="hide-menu">Faire une Proforma</span>
                </a>
            </li>

            
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Factures</span>
          </li>
          
          <li class="sidebar-item">
            <a class="sidebar-link {{ Request::is('dashboard/factures') ? 'active' : '' }}" href="{{ route('dashboard.factures.index') }}"  aria-expanded="false">
              <span>
                <i class="ti ti-list"></i>
              </span>
              <span class="hide-menu">Mes Factures</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link " href="{{ route('dashboard.factures.index') }}"  aria-expanded="false">
              <span>
                <i class="ti ti-file-invoice"></i> 
              </span>
              <span class="hide-menu">Faire une Facture</span>
            </a>
          </li>

          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Gestions</span>
          </li>
          
         

          <li class="sidebar-item">
            <a class="sidebar-link {{ Request::is('dashboard/clients') ? 'active' : '' }}" href="{{ route('dashboard.clients.index') }}"  aria-expanded="false">
              <span>
                <i class="ti ti-user-plus"></i> 
              </span>
              <span class="hide-menu">Compte Clients</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link {{ Request::is('dashboard/designations') ? 'active' : '' }}" href="{{ route('dashboard.designations.index') }}"  aria-expanded="false">
              <span>
                <i class="ti ti-pencil"></i>
              </span>
              <span class="hide-menu">Désignations</span>
            </a>
          </li>

          @endif




          <!-- ------------------------------- -->
          <!-- end notification Dropdown -->
          <!-- ------------------------------- -->



          <!-- ------------------------------- -->
          <!-- end notification Dropdown -->
          <!-- ------------------------------- -->




          <!-- ------------------------------- -->
          <!-- end notification Dropdown -->
          <!-- ------------------------------- -->




          


          @if (Auth::user()->hasRole('Commercial'))
          <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Proforma</span>
          </li>

          <li class="sidebar-item">
              <a class="sidebar-link {{ Request::is('dashboard/devis') ? 'active' : '' }}" href="{{ route('dashboard.devis.index') }}"  aria-expanded="false">
                  <span>
                      <i class="ti ti-list"></i>
                  </span>
                  <span class="hide-menu">Mes Proforma</span>
              </a>
          </li>

          <li class="sidebar-item">
              <a class="sidebar-link {{ Request::is('dashboard/devis/create') ? 'active' : '' }}" href="{{ route('dashboard.devis.create') }}"  aria-expanded="false">
                  <span>
                      <i class="ti ti-receipt"></i> 
                  </span>
                  <span class="hide-menu">Faire une Proforma</span>
              </a>
          </li>

       
       

       
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Gestions</span>
        </li>
        
       

        <li class="sidebar-item">
          <a class="sidebar-link {{ Request::is('dashboard/clients') ? 'active' : '' }}" href="{{ route('dashboard.clients.index') }}"  aria-expanded="false">
            <span>
              <i class="ti ti-user-plus"></i> 
            </span>
            <span class="hide-menu">Compte Clients</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link {{ Request::is('dashboard/designations') ? 'active' : '' }}" href="{{ route('dashboard.designations.index') }}"  aria-expanded="false">
            <span>
              <i class="ti ti-pencil"></i>
            </span>
            <span class="hide-menu">Désignations</span>
          </a>
        </li>

        @endif
          
          
          <!-- ---------------------------------- -->
          <!-- PAGES -->
          <!-- ---------------------------------- -->
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Paramétrage</span>
          </li>
         
          <li class="sidebar-item">
            <a class="sidebar-link {{ Request::is('dashboard/profil') ? 'active' : '' }}" 
               href="{{ route('dashboard.profil') }}" 
               aria-expanded="false">
                <span>
                    <i class="ti ti-user-circle"></i>
                </span>
                <span class="hide-menu">Mon Profil</span>
            </a>
        </li>
        
        <!-- ------------------------------- -->
        <!-- Administrateur -->
        <!-- ------------------------------- -->

        @if (Auth::user()->hasRole('Administrateur'))

        
          <li class="sidebar-item">
              <a class="sidebar-link {{ Request::is('dashboard/users') ? 'active' : '' }}" 
                href="{{ route('dashboard.users.index') }}" 
                 
                aria-expanded="false">
                <span>
                  <i class="ti ti-user-cog"></i>
                </span>
                <span class="hide-menu">Accès Utilisateurs</span>
              </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link {{ Request::is('dashboard/banques') ? 'active' : '' }}" 
              href="{{ route('dashboard.banques.index') }}" 
               
              aria-expanded="false">
              <span>
                <i class="ti ti-wallet"></i>
              </span>
              <span class="hide-menu">Banques</span>
            </a>
          </li>
          @endif
          
          
          <!-- ---------------------------------- -->
          <!-- UI -->
          <!-- ---------------------------------- -->
          {{-- <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">UI</span>
          </li> --}}
          <!-- ---------------------------------- -->
          <!-- UI Elements -->
          <!-- ---------------------------------- -->
          {{-- <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-layout"></i>
              </span>
              <span class="hide-menu">Widgets</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              <li class="sidebar-item">
                <a href="widgets-cards.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Cards</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="widgets-banners.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Banner</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="widgets-charts.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Charts</span>
                </a>
              </li>

            </ul>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-layout-grid"></i>
              </span>
              <span class="hide-menu">UI Elements</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              
              
              <li class="sidebar-item">
                <a href="ui-buttons.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Buttons</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="ui-dropdowns.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Dropdowns</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="ui-modals.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Modals</span>
                </a>
              </li>
              
              <li class="sidebar-item">
                <a href="ui-tooltip-popover.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Tooltip & Popover</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="ui-notification.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Alerts</span>
                </a>
              </li>
              
              <li class="sidebar-item">
                <a href="ui-pagination.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Pagination</span>
                </a>
              </li>
              
              
              <li class="sidebar-item">
                <a href="ui-breadcrumb.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Breadcrumb</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="ui-offcanvas.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Offcanvas</span>
                </a>
              </li>
              
              
              <li class="sidebar-item">
                <a class="sidebar-link" href="page-faq.html" aria-expanded="false">
                  <span>
                    <i class="ti ti-help"></i>
                  </span>
                  <span class="hide-menu">FAQ</span>
                </a>
              </li>
              
              <li class="sidebar-item">
                <a href="ui-spinner.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Spinner</span>
                </a>
              </li>
              
            </ul>
          </li> --}}

          <!-- ---------------------------------- -->
          <!-- Cards -->
          <!-- ---------------------------------- -->
          {{-- <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-cards"></i>
              </span>
              <span class="hide-menu">Cards</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              
              <li class="sidebar-item">
                <a href="ui-card-customs.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Custom Cards</span>
                </a>
              </li>
              
              
            </ul>
          </li> --}}

          <!-- ---------------------------------- -->
          <!-- Component -->
          <!-- ---------------------------------- -->
          {{-- <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-components"></i>
              </span>
              <span class="hide-menu">Component</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              <li class="sidebar-item">
                <a href="component-sweetalert.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Sweet Alert</span>
                </a>
              </li>
              
              
              
              <li class="sidebar-item">
                <a href="component-toastr.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Toastr</span>
                </a>
              </li>
            </ul>
          </li> --}}

          <!-- ---------------------------------- -->
          <!-- Forms -->
          <!-- ---------------------------------- -->
          {{-- <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Forms</span>
          </li> --}}
          <!-- ---------------------------------- -->
          <!-- Form Elements -->
          <!-- ---------------------------------- -->
          {{-- <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-file-text"></i>
              </span>
              <span class="hide-menu">Form Elements</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              <li class="sidebar-item">
                <a href="form-inputs.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Forms Input</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="form-input-groups.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Input Groups</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="form-input-grid.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Input Grid</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="form-checkbox-radio.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Checkbox & Radios</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="form-bootstrap-switch.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Bootstrap Switch</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="form-select2.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Select2</span>
                </a>
              </li>
            </ul>
          </li> --}}

          <!-- ---------------------------------- -->
          <!-- Form Addons -->
          <!-- ---------------------------------- -->
          {{-- <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-qrcode"></i>
              </span>
              <span class="hide-menu">Form Addons</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              <li class="sidebar-item">
                <a href="form-dropzone.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Dropzone</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="form-mask.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Form Mask</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="form-typeahead.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Form Typehead</span>
                </a>
              </li>
            </ul>
          </li> --}}

          <!-- ---------------------------------- -->
          <!-- Form Validation -->
          <!-- ---------------------------------- -->
          {{-- <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-alert-circle"></i>
              </span>
              <span class="hide-menu">Form Validation</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              <li class="sidebar-item">
                <a href="form-bootstrap-validation.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Bootstrap Validation</span>
                </a>
              </li>
              
            </ul>
          </li> --}}

          <!-- ---------------------------------- -->
          <!-- Form Pickers -->
          <!-- ---------------------------------- -->
          {{-- <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-file-pencil"></i>
              </span>
              <span class="hide-menu">Form Pickers</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              
              <li class="sidebar-item">
                <a href="form-picker-bootstrap-rangepicker.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Bootstrap Rangepicker</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="form-picker-bootstrap-datepicker.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Bootstrap Datepicker</span>
                </a>
              </li>
              
            </ul>
          </li> --}}

          <!-- ---------------------------------- -->
          <!-- Form Editor -->
          <!-- ---------------------------------- -->
          {{-- <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-dna"></i>
              </span>
              <span class="hide-menu">Form Editor</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              <li class="sidebar-item">
                <a href="form-editor-quill.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Quill Editor</span>
                </a>
              </li>
              
            </ul>
          </li> --}}

          <!-- ---------------------------------- -->
          <!-- Form Input -->
          <!-- ---------------------------------- -->
          
          {{-- <li class="sidebar-item">
            <a class="sidebar-link" href="form-vertical.html" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-box-align-left"></i>
              </span>
              <span class="hide-menu">Form Vertical</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link" href="form-horizontal.html" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-box-align-bottom"></i>
              </span>
              <span class="hide-menu">Form Horizontal</span>
            </a>
          </li>
         
          <li class="sidebar-item">
            <a class="sidebar-link" href="form-row-separator.html" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-separator-horizontal"></i>
              </span>
              <span class="hide-menu">Row Separator</span>
            </a>
          </li>
          
          <li class="sidebar-item">
            <a class="sidebar-link" href="form-detail.html" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-file-description"></i>
              </span>
              <span class="hide-menu">Form Detail</span>
            </a>
          </li> --}}
          
         
          <!-- ---------------------------------- -->
          <!-- Form Wizard -->
          <!-- ---------------------------------- -->
          {{-- <li class="sidebar-item">
            <a class="sidebar-link" href="form-wizard.html" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-files"></i>
              </span>
              <span class="hide-menu">Form Wizard</span>
            </a>
          </li> --}}
          <!-- ---------------------------------- -->
          <!-- Form Repeater -->
          <!-- ---------------------------------- -->
          
          <!-- ---------------------------------- -->
          <!-- Tables -->
          <!-- ---------------------------------- -->
          {{-- <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Tables</span>
          </li> --}}
         
          
          <!-- ---------------------------------- -->
          <!-- Datatable -->
          <!-- ---------------------------------- -->
          {{-- <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-air-conditioning-disabled"></i>
              </span>
              <span class="hide-menu">Datatables</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              <li class="sidebar-item">
                <a href="table-datatable-basic.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Basic Initialisation</span>
                </a>
              </li>
             
              
            </ul>
          </li> --}}
          <!-- ---------------------------------- -->
          <!-- Table Jsgrid -->
          <!-- ---------------------------------- -->

          
         

         

          <!-- ---------------------------------- -->
          <!-- Icons -->
          <!-- ---------------------------------- -->
          {{-- <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Icons</span>
          </li> --}}
          <!-- ---------------------------------- -->
          <!-- Tabler Icon -->
          <!-- ---------------------------------- -->
          {{-- <li class="sidebar-item">
            <a class="sidebar-link sidebar-link" href="icon-tabler.html" aria-expanded="false">
              <span class="rounded-3">
                <i class="ti ti-archive"></i>
              </span>
              <span class="hide-menu">Tabler Icon</span>
            </a>
          </li> --}}
          <!-- ---------------------------------- -->
          <!-- Solar Icon -->
          <!-- ---------------------------------- -->
          {{-- <li class="sidebar-item">
            <a class="sidebar-link sidebar-link" href="icon-solar.html" aria-expanded="false">
              <span class="rounded-3">
                <i class="ti ti-mood-smile"></i>
              </span>
              <span class="hide-menu">Solar Icon</span>
            </a>
          </li> --}}
          <!-- ---------------------------------- -->
          <!-- AUTH -->
          <!-- ---------------------------------- -->
          {{-- <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">AUTH</span>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link sidebar-link" href="authentication-error.html" aria-expanded="false">
              <span class="rounded-3">
                <i class="ti ti-alert-circle"></i>
              </span>
              <span class="hide-menu">Error</span>
            </a>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-login"></i>
              </span>
              <span class="hide-menu">Login</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              
              <li class="sidebar-item">
                <a href="authentication-login2.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Boxed Login</span>
                </a>
              </li>
            </ul>
          </li>
          <li class="sidebar-item">
            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-user-plus"></i>
              </span>
              <span class="hide-menu">Register</span>
            </a>
            <ul aria-expanded="false" class="collapse first-level">
              
              <li class="sidebar-item">
                <a href="authentication-register2.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Boxed Register</span>
                </a>
              </li>
            </ul>
          </li> --}}
          {{-- <li class="sidebar-item">
           
            <ul aria-expanded="false" class="collapse first-level">
              <li class="sidebar-item">
                <a href="authentication-forgot-password.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Side Forgot Password</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="authentication-forgot-password2.html" class="sidebar-link">
                  <div class="round-16 d-flex align-items-center justify-content-center">
                    <i class="ti ti-circle"></i>
                  </div>
                  <span class="hide-menu">Boxed Forgot Password</span>
                </a>
              </li>
            </ul>
          </li>
         
          <li class="sidebar-item">
            <a class="sidebar-link sidebar-link" href="authentication-maintenance.html" aria-expanded="false">
              <span class="rounded-3">
                <i class="ti ti-settings"></i>
              </span>
              <span class="hide-menu">Maintenance</span>
            </a>
          </li> --}}
         
          <!-- ---------------------------------- -->
          <!-- OTHER -->
          <!-- ---------------------------------- -->
          {{-- <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">OTHER</span>
          </li>
          
          <li class="sidebar-item">
            <a class="sidebar-link link-disabled" href="#disabled" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-ban"></i>
              </span>
              <span class="hide-menu">Disabled</span>
            </a>
          </li> --}}
         
          
        
          {{-- <li class="sidebar-item">
            <a class="sidebar-link" href="https://google.com" aria-expanded="false">
              <span class="d-flex">
                <i class="ti ti-star"></i>
              </span>
              <span class="hide-menu">External Link</span>
            </a>
          </li> --}}
        </ul>
      </nav>

      <div class="fixed-profile p-3 mx-4 mb-2 bg-secondary-subtle rounded mt-3">
        <div class="hstack gap-3">
          <div class="john-img">
            <img src="{{ asset(auth()->user()->image) }}" class="rounded-circle" width="40" height="40" alt="modernize-img">
          </div>
          <div class="john-title">
            <h6 class="mb-0 fs-4 fw-semibold">{{ auth()->user()->name }}</h6>
            <span class="fs-2">{{ Auth::user()->roles->first()->name }}</span>
          </div>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="border-0 bg-transparent text-primary ms-auto" tabindex="0" type="submit" aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Se déconnecter">
                <i class="ti ti-power fs-6"></i>
            </button>
        </form>
        
        </div>
      </div>

      <!-- ---------------------------------- -->
      <!-- Start Vertical Layout Sidebar -->
      <!-- ---------------------------------- -->
    </div>
  </aside>