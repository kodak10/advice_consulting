@extends('administration.layouts.master')

@section('content')
<div class="position-relative overflow-hidden min-vh-100 w-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-lg-4">
          <div class="text-center">
            <img src="{{ asset('adminAssets/images/backgrounds/maintenance.svg') }}" alt="modernize-img" class="img-fluid" width="500">
            <h4 class="fw-semibold mb-7">Le site Web est en construction. Revenez plus tard !</h4>
            <a class="btn btn-primary" href="{{ route('dashboard.') }}" role="button">Retourner à la page d'accueil</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection