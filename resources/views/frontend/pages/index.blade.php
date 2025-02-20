@extends('frontend.layouts.master')

@section('content')
<section>
    <div class="bg-primary-subtle py-lg-12 py-5">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6">
            
          </div>
          <div class="col-lg-6">
            <h2 class="fs-12 fw-bolder">Gérez vos Proforma et factures en toute simplicité</h2>
            <div class="d-flex mt-4 gap-3 flex-sm-nowrap flex-wrap">
              <a href="/login" class="btn btn-primary px-9 py-6">Se Connecté</a>
              <a href="#howtomatch" class="btn btn-outline-primary px-9 py-6">Comment ça marche ?</a>
            </div>
          </div>
        </div>
      </div>
    </div>
</section>

<div class="process-section" id="howtomatch">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-lg-12">
                <div class="hendrio-section-title text-center padding-lg">
                    <h4> Comment ça marche ? </h4>
                    <h1> Suivez les <span>étapes</span> </h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="single-process-box upper">
                    <div class="process-thumb">
                        <img src="{{ asset('assets/images/process1.png') }}" alt="">
                        <div class="process-number">
                            <span>01</span>
                        </div>
                    </div>
                    <div class="process-content">
                        <h4 class="process-title">Connexion</h4>
                        <p class="process-desc">Vous aurez besoin de votre adresse e-mail et de votre mot de passe pour vous connecter.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="single-process-box">
                    <div class="process-thumb">
                        <img src="{{ asset('assets/images/process3.png') }}" alt="">
                        <div class="process-number">
                            <span>02</span>
                        </div>
                    </div>
                    <div class="process-content">
                        <h4 class="process-title">Faire une proforma</h4>
                        <p class="process-desc">Dans l'onglet de gauche, cliquez sur <strong>Faire une Proforma</strong> et renseignez les informations nécessaires.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="single-process-box">
                    <div class="process-thumb">
                        <img src="{{ asset('assets/images/process3.png') }}" alt="">
                        <div class="process-number">
                            <span>03</span>
                        </div>
                    </div>
                    <div class="process-content">
                        <h4 class="process-title">Faire une facture</h4>
                        <p class="process-desc">Dans l'onglet de gauche, cliquez sur <strong>Faire une facture</strong> et renseignez les informations nécessaires.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="single-process-box">
                    <div class="process-thumb">
                        <img src="{{ asset('assets/images/process4.png') }}" alt="">
                        <div class="process-number">
                            <span>04</span>
                        </div>
                    </div>
                    <div class="process-content">
                        <h4 class="process-title">Terminé</h4>
                        <p class="process-desc">Votre Proforma ou facture est maintenant dans le système et prêt à être consulté à tout moment et en tout lieu.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        .bg-primary-subtle {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('{{ asset('assets/images/banner.jpg') }}') !important;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
@endpush

