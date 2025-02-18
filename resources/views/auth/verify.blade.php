@extends('frontend.layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Vérifiez votre adresse e-mail') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
                        </div>
                    @endif

                    {{ __('Avant de continuer, veuillez vérifier votre courrier électronique pour un lien de vérification.') }}
                    {{ __("Si vous n'avez pas reçu l'e-mail") }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Cliquez ici pour en demander un autre') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .header-fp{
        background-color: #ffffff;
        margin-bottom: 30px;
    }
    .header-fp .nav-link {
        color: #000 !important;
    }
    .header-fp .navbar .navbar-nav .nav-link.active{
        color: #000 !important;
    }
    .navbar-toggler{
            color: #000 !important;
        }
</style>
@endpush