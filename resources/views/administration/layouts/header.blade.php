<header class="topbar">
    <div class="with-vertical"><!-- ---------------------------------- -->
      <!-- Start Vertical Layout Header -->
      <!-- ---------------------------------- -->
      <nav class="navbar navbar-expand-lg p-0">
        <ul class="navbar-nav">
          <li class="nav-item nav-icon-hover-bg rounded-circle ms-n2">
            <a class="nav-link sidebartoggler" id="headerCollapse" href="javascript:void(0)">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          {{-- <li class="nav-item nav-icon-hover-bg rounded-circle d-none d-lg-flex">
            <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
              <i class="ti ti-search"></i>
            </a>
          </li> --}}
        </ul>

       

        <div class="d-block d-lg-none py-4">
          <a href="{{ route('dashboard.') }}" class="text-nowrap logo-img">
            <img src="{{ asset('adminAssets/images/logos/favicon.png') }}" class="dark-logo" alt="Logo" style="height: 50px">
            <img src="{{ asset('adminAssets/images/logos/favicon.png') }}" class="light-logo" alt="Logo" style="height: 50px">
          </a>
        </div>
        <a class="navbar-toggler nav-icon-hover-bg rounded-circle p-0 mx-0 border-0" href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <i class="ti ti-dots fs-7"></i>
        </a>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
          <div class="d-flex align-items-center justify-content-between">
            
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-center">
              <!-- ------------------------------- -->
              <!-- start language Dropdown -->
              <!-- ------------------------------- -->
              <li class="nav-item nav-icon-hover-bg rounded-circle">
                <a class="nav-link moon dark-layout" href="javascript:void(0)">
                  <i class="ti ti-moon moon"></i>
                </a>
                <a class="nav-link sun light-layout" href="javascript:void(0)">
                  <i class="ti ti-sun sun"></i>
                </a>
              </li>
              

              {{-- <ul id="notification-list">
                <!-- Les notifications apparaîtront ici -->
              </ul>
               --}}
              <li class="nav-item nav-icon-hover-bg rounded-circle dropdown">
                  <a class="nav-link position-relative" href="javascript:void(0)" id="drop2" aria-expanded="false">
                      <i class="ti ti-bell-ringing"></i>
                      <div class="notification bg-primary rounded-circle">0</div> <!-- Compteur de notifications -->
                  </a>
                  <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                      <div class="d-flex align-items-center justify-content-between py-3 px-7">
                          <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                          <span class="badge text-bg-primary rounded-4 px-3 py-1 lh-sm" id="notification-count">5 Nouveau</span>
                      </div>
                      <div id="notification-list"></div> <!-- Liste des notifications -->
                      <div class="py-6 px-7 mb-1">
                          <button class="btn btn-outline-primary w-100">Voir toutes les notifications</button>
                      </div>
                  </div>
              </li>
            
            
              <!-- ------------------------------- -->
              <!-- end notification Dropdown -->
              <!-- ------------------------------- -->

              <!-- ------------------------------- -->
              <!-- start profile Dropdown -->
              <!-- ------------------------------- -->
              <li class="nav-item dropdown">
                <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" aria-expanded="false">
                  <div class="d-flex align-items-center">
                    <div class="user-profile-img mr-3">
                      <img src="{{ asset(auth()->user()->image) }}" class="rounded-circle mr-3" width="35" height="35" alt="modernize-img">
                    </div>
                    <h5 class="fw-semibold mb-0 fs-3 ml-3">
                      {{ auth()->user()->name }}
                    </h5>
                  </div>
                </a>
                <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                  <div class="profile-dropdown position-relative" data-simplebar="">
                    <div class="py-3 px-7 pb-0">
                      <h5 class="mb-0 fs-5 fw-semibold">Profil</h5>
                    </div>
                    <div class="d-flex align-items-center py-9 mx-7 border-bottom">
                      <img src="{{ asset(auth()->user()->image) }}" class="rounded-circle" width="80" height="80" alt="modernize-img">
                      <div class="ms-3">
                        <h5 class="mb-1 fs-3">{{ auth()->user()->name }}</h5>
                        <span class="mb-1 d-block">{{ Auth::user()->roles->first()->name }}</span>
                        <p class="mb-0 d-flex align-items-center gap-2">
                          <i class="ti ti-mail fs-4"></i> {{ auth()->user()->email }}
                        </p>
                      </div>
                    </div>
                    <div class="message-body">
                      <a href="{{ route('dashboard.profil') }}" class="py-8 px-7 mt-8 d-flex align-items-center">
                        <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                          <img src="{{ asset('adminAssets/images/svgs/icon-account.svg') }}" alt="modernize-img" width="24" height="24">
                        </span>
                        <div class="w-100 ps-3">
                          <h6 class="mb-1 fs-3 fw-semibold lh-base">Mon Profil</h6>
                          <span class="fs-2 d-block text-body-secondary">Paramètres du compte</span>
                        </div>
                      </a>
                      <a href="{{ route('dashboard.messagerie.index') }}" class="py-8 px-7 d-flex align-items-center">
                        <span class="d-flex align-items-center justify-content-center text-bg-light rounded-1 p-6">
                          <img src="{{ asset('adminAssets/images/svgs/icon-inbox.svg') }}" alt="modernize-img" width="24" height="24">
                        </span>
                        <div class="w-100 ps-3">
                          <h6 class="mb-1 fs-3 fw-semibold lh-base">Ma boîte de réception</h6>
                          <span class="fs-2 d-block text-body-secondary">Notifications</span>
                        </div>
                      </a>
                      
                    </div>
                    
                  </div>
                </div>
              </li>
              <!-- ------------------------------- -->
              <!-- end profile Dropdown -->
              <!-- ------------------------------- -->
            </ul>
          </div>
        </div>
      </nav>
      <!-- ---------------------------------- -->
      <!-- End Vertical Layout Header -->
      <!-- ---------------------------------- -->

     
    </div>
    <div class="app-header with-horizontal">
      <nav class="navbar navbar-expand-xl container-fluid p-0">
        <ul class="navbar-nav align-items-center">
          <li class="nav-item nav-icon-hover-bg rounded-circle d-flex d-xl-none ms-n2">
            <a class="nav-link sidebartoggler" id="sidebarCollapse" href="javascript:void(0)">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
          <li class="nav-item d-none d-xl-block">
            <a href="index.html" class="text-nowrap nav-link">
              <img src="{{ asset('adminAssets/images/logos/logo.png') }}" class="dark-logo" width="180" alt="modernize-img">
              <img src="{{ asset('adminAssets/images/logos/logo.png') }}" class="light-logo" width="180" alt="modernize-img">
            </a>
          </li>
          <li class="nav-item nav-icon-hover-bg rounded-circle d-none d-xl-flex">
            <a class="nav-link" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">
              <i class="ti ti-search"></i>
            </a>
          </li>
        </ul>
       
      </nav>
    </div>
  </header>