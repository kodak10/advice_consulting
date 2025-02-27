<aside class="left-sidebar with-vertical">
    <div>
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
                    <span class="hide-menu">Mes Proformas</span>
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
                  <span class="hide-menu">Mes Proformas</span>
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

        @if (Auth::user()->hasRole('Daf'))

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