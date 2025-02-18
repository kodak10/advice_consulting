@extends('frontend.layouts.master')

@section('content')
<div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 col-xxl-3 auth-card">
          <div class="card mb-0">
            <div class="card-body pt-5">
              <a href="/" class="text-nowrap logo-img text-center d-block mb-4 w-100">
                <img src="{{ asset('adminAssets/images/logos/logo.png') }}" class="light-logo" alt="Logo-light" style="height: 90px">
              </a>
              <div class="mb-5 text-center">
                <p class="mb-0 ">
                    Veuillez saisir l'adresse e-mail associée à votre compte et nous vous enverrons un lien pour réinitialiser votre mot de passe.
                </p>
              </div>
              <form>
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Email</label>
                  <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <a href="javascript:void(0)" class="btn btn-primary w-100 py-8 mb-3">Envoyer un lien de réinitialisation</a>
                <a href="/login" class="btn bg-primary-subtle text-primary w-100 py-8">Retour à la connexion</a>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

