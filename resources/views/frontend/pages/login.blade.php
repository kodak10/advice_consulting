@extends('frontend.layouts.master')

@section('content')
<div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 col-xxl-3 auth-card">
          <div class="card mb-0">
            <div class="card-body">
              <a href="/" class="text-nowrap logo-img text-center d-block mb-5 w-100">
                <img src="{{ asset('adminAssets/images/logos/logo.png') }}" class="dark-light" alt="Logo-Dark" style="height: 90px">
              </a>
              
              <div class="position-relative text-center my-4">
                <p class="mb-0 fs-4 px-3 d-inline-block bg-body text-dark z-index-5 position-relative">Vos identifications
                </p>
                <span class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
              </div>
              <form>
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Email</label>
                  <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="mb-4">
                  <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
                  <input type="password" class="form-control" id="exampleInputPassword1">
                </div>
                <div class="d-flex align-items-center justify-content-between mb-4">
                  <div class="form-check">
                    <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked="">
                    <label class="form-check-label text-dark" for="flexCheckChecked">
                      Se souvenir de moi
                    </label>
                  </div>
                  <a class="text-primary fw-medium" href="/password-forgot">Mot de passe oublié ?</a>
                </div>
                <a href="#" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Se Connecter</a>
                
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection