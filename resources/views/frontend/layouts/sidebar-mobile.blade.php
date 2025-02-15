<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
      <a href="/">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo-light" style="height: 100px">
      </a>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <ul class="list-unstyled ps-0">
        <li class="mb-1">
          <a href="/" class="px-0 fs-4 d-block text-dark link-primary w-100 py-2">
            Accueil
          </a>
        </li>

        <li class="mb-1">
          <a href="#howtomatch" class="px-0 fs-4 d-block w-100 py-2 text-dark link-primary">
            Comment ça marche ?
          </a>
        </li>

        @if(auth()->check())
            <li class="mb-1">
                <a href="{{ route('dashboard.') }}" class="px-0 fs-4 d-block w-100 py-2 text-dark link-primary">
                    Dashboard
                </a>
            </li>
        @endif


        <li class="mt-3">
          <a href="/login" class="btn btn-primary w-100">Se Connecté</a>
        </li>
      </ul>
    </div>
  </div>