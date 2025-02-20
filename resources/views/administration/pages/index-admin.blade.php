@extends('administration.layouts.master')

@section('content')
<div class="container-fluid">
    <h1 class="fw-semibold mb-3 fs-6 text-center">
      BIENVENUE SUR LA L'ESPACE DE GESTION DE PROFORMA ET FACTURES
    
    </h1>
    <div class="row">
      <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100 bg-primary-subtle overflow-hidden shadow-none">
          <div class="card-body position-relative">
            <div class="row">
              <div class="col-sm-7">
                <div class="d-flex align-items-center mb-7">
                  <div class="rounded-circle overflow-hidden me-6">
                    <img src="{{ asset(auth()->user()->image) }}" alt="modernize-img" width="40" height="40">
                  </div>
                  <div class="text">
                    <span>
                      {{ now()->hour < 18 ? 'Bonjour' : 'Bonsoir' }}
                    
                      <h5 class="fw-semibold mb-0 fs-5">
                        {{ auth()->user()->name }}
                       
                      </h5>
                    </span>
                    
                    <span>{{ Auth::user()->roles->first()->name }}</span>

                  </div>
                  
                </div>

                
              </div>
              <div class="col-sm-5">
                <div class="welcome-bg-img mb-n7 text-end">
                  <img src="{{ asset('adminAssets/images/backgrounds/welcome-bg.svg') }}" alt="modernize-img" class="img-fluid">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      
     
      <div class="col-lg-4">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                      <div class="row alig n-items-start">
                        <div class="col-12">
                          <h4 class="card-title mb-9 fw-semibold"> Nombres d'utilisateurs </h4>
                          <div class="d-flex align-items-center mb-3">
                            <h4 class="fw-semibold mb-0 me-8">{{ $userTotal }}</h4>
                            
                          </div>
                        </div>
                        
                      </div>
                      
                    </div>
                </div>
            </div>
          <div class="col-sm-6 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body">
                <div class="row">
                    <h4 class="card-title mb-9 fw-semibold"> Actif </h4>

                </div>
                
                </h4>
                <p class="mb-0">{{ $userActif }}</p>
              </div>
            </div>
          </div>
          <div class="col-sm-6 d-flex align-items-stretch">
            <div class="card w-100">
              <div class="card-body">
                <div class="row">
                    <h4 class="card-title mb-9 fw-semibold"> Inactif </h4>

                </div>
                
                </h4>
                <p class="mb-0">{{ $userInactif }}</p>
              </div>
            </div>
          </div>
        </div>
        
      </div>
      
     
      
      
      <div class="col-md-12 col-lg-8 d-flex align-items-stretch">
        <div class="card w-100">
          <div class="card-body">
            <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
              <div class="mb-3 mb-sm-0">
                <h4 class="card-title fw-semibold">Liste des Utilisateurs</h4>
              </div>
              <div>
                <a href="{{ route('dashboard.users.index') }}" class="btn btn-success">Ajouter un Utilisateur</a>
              </div>
            </div>
            <div class="table-responsive">
              <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
                <thead>
                  <tr class="text-muted fw-semibold">
                    <th scope="col" class="ps-0">Utilisateur</th>
                    <th scope="col">Email</th>
                    <th scope="col">Pays</th>
                    <th scope="col">Rôle</th>
                    <th scope="col">Status</th>
                  </tr>
                </thead>
                <tbody class="border-top">
                    @foreach($users as $user)
                        <tr>
                            <td class="ps-0">
                                <div class="d-flex align-items-center">
                                    <div class="me-2 pe-1">
                                        <img src="{{ asset($user->image) }}" class="rounded-2" width="48" height="48" alt="user-avatar">
                                    </div>
                                    <div>
                                        <h6 class="text-dark mb-1">{{ $user->name }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <p class="mb-0 fs-3 text-dark">{{ $user->email }}</p>
                            </td>
                            <td>
                                <span class="badge text-dark py-1 w-85">
                                    {{ $user->pays->name }}
                                </span>
                            </td>
                            <td>
                                {{-- <span>{{ Auth::user()->roles->first()->name }}</span> --}}

                                <p class="fs-3 text-dark mb-0">{{ ucfirst($user->roles->first()->name) }}</p>
                            </td>
                            <td>
                                <span class="badge text-dark py-1 w-85 bg-{{ $user->status }} text-{{ $user->status }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection