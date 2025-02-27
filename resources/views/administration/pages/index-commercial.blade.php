@extends('administration.layouts.master')

@section('content')
<div class="container-fluid">
    <h1 class="fw-semibold mb-3 fs-6 text-center">
      BIENVENUE SUR LA L'ESPACE DE GESTION DES FACTURES    </h1>
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
      
      
     
      
      
      <div class="col-md-12 col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
          <div class="card-body">
            <div class="d-sm-flex d-block align-items-center justify-content-between mb-3">
              <div class="mb-3 mb-sm-0">
                <h4 class="card-title fw-semibold">Liste des Proformas</h4>
              </div>
              <div>
                <div class="input-daterange input-group mr-3" id="date-range">
                  <input type="text" class="form-control" name="start" id="start-date" placeholder="Date début">
                  <span class="input-group-text bg-primary b-0 text-white">TO</span>
                  <input type="text" class="form-control" name="end" id="end-date" placeholder="Date fin">
              </div>

              <a href="{{ route('dashboard.devis.exportCsv') }}" class="btn btn-success">
                Exporter en CSV
            </a>              
          </div>
            </div>

            <div class="row">
              <div class="col-md-8 col-xl-3">
                @if(session('success'))
                    <div class="alert alert-success text-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger text-danger" role="alert">
                        {!! session('error') !!}
                    </div>
                @endif
            </div>
            </div>
            <div class="table-responsive">
                <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
                  <thead>
                    <!-- start row -->
                    <tr>
                      <th>Date</th>
                        <th>N° Proforma</th>
                        <th>Client</th>
                        <th>Coût</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                    <!-- end row -->
                  </thead>
                  <tbody>
                    @forelse ($devis as $devi)
                    <tr>
                      <td>
                        <h6 class="mb-0">{{ $devi->created_at }}</h6>
                      </td>
                        <td>
                            <h6 class="mb-0">{{ $devi->num_proforma }}</h6>
                        </td>
                        <td>{{ $devi->client->nom }}</td>
                        <td>{{ $devi->details->sum('total') }} {{ $devi->devise }}</td>
                        <td>{{ $devi->status ?? 'Non renseigné' }}</td>
                        <td>
                          <div class="action-btn text-center">
                            <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger">
                              <i class="ti ti-download fs-5"></i>
                            </a>
                          
    
                          <a href="{{ route('dashboard.devis.validate', $devi->id) }}" class="text-primary me-2" title="Valider">
                            <i class="ti ti-navigation-check"></i>
                        </a>
    
                              <a href="{{ route('dashboard.devis.edit', $devi->id) }}" class="text-primary me-2" title="Modifier">
                                  <i class="ti ti-pencil fs-5"></i>
                              </a>
                      
                              <form id="delete-form-{{ $devi->id }}" action="{{ route('dashboard.devis.destroy', $devi->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-link text-danger p-0 border-0" title="Supprimer" onclick="confirmDelete({{ $devi->id }})">
                                    <i class="ti ti-trash fs-5"></i>
                                </button>
                            </form>
                          </div>
                      </td>
                      
                    </tr>
    
    
                    
                    @empty
                        Aucune Proforma enregistrée.
                    @endforelse
                    
                </tbody>
                
                  <tfoot>
                    <!-- start row -->
                    <tr>
                      <th>Date</th>
                        <th>N° Proforma</th>
                        <th>Client</th>
                        <th>Coût</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                    <!-- end row -->
                  </tfoot>
                </table>
            </div>
          </div>
        </div>
      </div>

      
    </div>
  </div>
@endsection