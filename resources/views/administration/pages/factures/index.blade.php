@extends('administration.layouts.master')

@section('content')
<div class="container-fluid">
           
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Mes Factures</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a class="text-muted text-decoration-none" href="{{ route('dashboard.') }}">Accueil</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Mes Factures</li>
              </ol>
            </nav>
          </div>
          <div class="col-3">
            <div class="text-center mb-n5">
              <img src="{{ asset('adminAssets/images/breadcrumb/ChatBc.png') }}" alt="modernize-img" class="img-fluid mb-n4">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="widget-content searchable-container list">

      <div class="card card-body">
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
          <div class="col-md-4 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
    
            <a href="" class="btn btn-primary">
                <i class="ti ti-users text-white me-1 fs-5"></i> 
                Faire une facture
            </a>
              
          </div>
        </div>
      </div>

      


     @if(Auth::user()->role('Commercial'))
      <div class="card card-body">
        <div class="table-responsive mb-5">
            <h5>
                Mes factures
            </h5>
            <table id="zero_config4" class="table table-striped table-bordered text-nowrap align-middle">
              <thead>
                <tr>
                    <th>Date</th>
                    <th>N° Proforma</th>
                    <th>Client</th>
                    <th>Coût</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
              </thead>
                <tbody>
                  @forelse ($factureCommercials as $factureCommercial)
                  <tr>
                    <td>
                      <h6 class="mb-0">{{ $factureCommercial->created_at }}</h6>
                    </td>
                    <td>
                      <h6 class="mb-0">{{ $factureCommercial->devis->num_proforma }}</h6>
                    </td>
                    <td>
                      <h6 class="mb-0">{{ $factureCommercial->devis->client->nom }}</h6>
                    </td>
                    <td>
                      <h6 class="mb-0">{{ $factureCommercial->devis->total_ttc }} {{ $factureCommercial->devis->devise }}</h6>
                    </td>
                    <td>
                      <h6 class="mb-0">{{ $factureCommercial->devis->status }}</h6>
                    </td>
                  
                    
                      <td>
                          
                          <a href="{{ route('dashboard.factures.download', $factureCommercial->id) }}" class="text-primary me-2" title="Télécharger">
                            <i class="ti ti-download fs-5"></i>
                          </a>
                        
                        
                      </td>
                    
                  </tr>

                  
                  @empty
                      Aucune Proforma
                  @endforelse
                  
                </tbody>


              

            
            
              <tfoot>
                <tr>
                    <th>Date</th>
                    <th>N° Proforma</th>
                    <th>Client</th>
                    <th>Coût</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
              </tfoot>
            </table>
        </div>

      
      </div>
      
     @endif
      

     @if(Auth::user()->hasAnyRole(['Daf', 'Comptable']))
     <div class="card card-body">
        <div class="table-responsive mb-5">
            <h5>
                En attente de Facture
            </h5>
            <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
              <thead>
                <tr>
                  <th>Date</th>

                    <th>N° Proforma</th>
                    @if(Auth::user()->hasRole('Daf'))
                    <th>Pays</th>

                    @endif
                    <th>Etabli Par</th>
                    <th>Client</th>
                    <th>Coût</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
              </thead>
                @if (Auth::user()->hasRole('Daf'))
                <tbody>
                  @forelse ($all_devis as $devi)
                  <tr>
                    <td>
                      <h6 class="mb-0">{{ $devi->created_at }}</h6>
                    </td>
                      <td>
                          <h6 class="mb-0">{{ $devi->num_proforma }}</h6>
                      </td>
                      @if(Auth::user()->hasRole('Daf'))
                        <td>{{ $devi->user->name }}</td>

                      @endif
                      <td>{{ $devi->client->nom }}</td>
                      <td>{{ $devi->details->sum('total') }} {{ $devi->devise }}</td>
                      <td class=""><strong>{{ $devi->user->name }}</strong></td>
                      <td>{{ $devi->status ?? 'Non renseigné' }}</td>
  
                      <td>
                          <a href="{{ route('dashboard.factures.refuse', $devi->id) }}" class="text-danger me-2" title="Réfuser">
                              <i class="ti ti-square-rounded-x"></i>
                          </a>
                          <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger">
                            <i class="ti ti-download fs-5"></i>
                          </a>
                          <a href="{{ route('dashboard.factures.create', $devi->id) }}" class="text-success me-2" title="Etablir la facture">
                            <i class="ti ti-clipboard-list"></i>
                        </a>
                        
                      </td>
                    
                  </tr>
  
                  
                  @empty
                      Aucune Proforma
                  @endforelse
                  
                </tbody>
                @endif


                @if(Auth::user()->hasRole('Comptable'))
                <tbody>
                  @forelse ($devis_pays as $devi)
                  <tr>
                    <td>
                      <h6 class="mb-0">{{ $devi->created_at }}</h6>
                    </td>
                      <td>
                          <h6 class="mb-0">{{ $devi->num_proforma }}</h6>
                      </td>
                      <td class=""><strong>{{ $devi->user->name }}</strong></td>
                      <td>{{ $devi->client->nom }}</td>
                      <td>{{ $devi->details->sum('total') }} {{ $devi->devise }}</td>
                      <td>{{ $devi->status ?? 'Non renseigné' }}</td>
                      <td>
                          <a href="{{ route('dashboard.factures.refuse', $devi->id) }}" class="text-danger me-2" title="Réfuser">
                              <i class="ti ti-square-rounded-x"></i>
                          </a>
                          <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger">
                            <i class="ti ti-download fs-5"></i>
                          </a>
                          <a href="{{ route('dashboard.factures.create', $devi->id) }}" class="text-success me-2" title="Etablir la facture">
                            <i class="ti ti-clipboard-list"></i>
                        </a>
                        
                      </td>
                    
                  </tr>
  
                  @empty
                      Aucune Proforma
                  @endforelse
                  
                </tbody>
                @endif

             
            
              <tfoot>
                <tr>
                  <th>Date</th>

                    <th>N° Proforma</th>
                    @if(Auth::user()->hasRole('Daf'))
                    <th>Pays</th>

                    @endif
                    <th>Etabli Par</th>
                    <th>Client</th>
                    <th>Coût</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
              </tfoot>
            </table>
        </div>

       
      </div>

      <div class="card card-body">
        <div class="table-responsive">
          <div class="table-responsive mt-5">
            <h5>
              Liste des factures
            </h5>
              <table id="zero_config2" class="table table-striped table-bordered text-nowrap align-middle">
                <thead>
                  <tr>
                    <th>Date</th>

                      <th>N° Proforma</th>

                      @if(Auth::user()->hasRole('Daf'))
                        <th>Pays</th>
                      @endif
                      <th>Etabli Par</th>
                      <th>Client</th>
                      <th>Coût</th>
                      <th>Statut</th>
                      <th>Action</th>

                  </tr>
                </thead>

                @if(Auth::user()->hasRole('Daf'))
                <tbody>
                  @forelse ($all_factures as $facture)
                  <tr>
                    <td>
                      <h6 class="mb-0">{{ $facture->created_at }}</h6>
                    </td>
                      <td>
                          <h6 class="mb-0">{{ $facture->numero }}</h6>
                      </td>
                      <td>
                        <h6 class="mb-0">{{ $facture->pays->name }}</h6>
                    </td>
                    <td>
                      <h6 class="mb-0">{{ $facture->user->name }}</h6>
                    </td>
                     <td>
                        {{ $facture->devis->client->nom }}
                     </td>
                      <td>{{ $facture->devis->details->sum('total') }} {{ $facture->devis->devise }}</td>
                      <td>{{ $facture->devis->status ?? 'Non renseigné' }}</td>
  
                      <td>
                        <a href="{{ route('dashboard.factures.download', $facture->id) }}" class="text-primary me-2" title="Télécharger">
                          <i class="ti ti-download fs-5"></i>
                        </a>
                        @if(Auth::user()->hasRole('Daf'))
                          <a href="{{ route('dashboard.factures.validate', $facture->id) }}" class="text-success me-2" title="Approuvé la facture">
                            <i class="ti ti-download fs-5"></i>
                          </a>
                        @endif
                        
                      </td>
                    
                  </tr>
  
                  @empty
                      Aucune Facture enregistrée.
                  @endforelse
                  
                </tbody>
                @endif
                
                @if(Auth::user()->hasRole('Comptable'))

                <tbody>
                  @forelse ($factures_pays as $facture)
                  <tr>
                    <td>
                      <h6 class="mb-0">{{ $facture->created_at }}</h6>
                    </td>
                      <td>
                          <h6 class="mb-0">{{ $facture->numero }}</h6>
                      </td>
                      <td>
                        <h6 class="mb-0">{{ $facture->user->name }}</h6>
                      </td>
                     <td>
                        {{ $facture->devis->client->nom }}
                     </td>
                      <td>{{ $facture->devis->details->sum('total') }} {{ $facture->devis->devise }}</td>
                      <td>{{ $facture->devis->status ?? 'Non renseigné' }}</td>
  
                      <td>
                        <a href="{{ route('dashboard.factures.download', $facture->id) }}" class="text-primary me-2" title="Télécharger">
                          <i class="ti ti-download fs-5"></i>
                        </a>
                        
                      </td>
                    
                  </tr>
  
                  @empty
                      Aucune Facture enregistrée.
                  @endforelse
                  
                </tbody>
                  
                @endif
              
                <tfoot>
                  <tr>
                    <th>Date</th>

                      <th>N° Proforma</th>
                      @if(Auth::user()->hasRole('Daf'))
                        <th>Pays</th>
                      @endif
                      <th>Etabli Par</th>
                      <th>Client</th>
                      <th>Coût</th>
                      <th>Statut</th>
                      <th>Action</th>

                  </tr>
                </tfoot>
              </table>
              
          </div>
        </div>
      </div>

      
    </div>
        
      @endif
     
      <div class="card card-body">
        <div class="table-responsive">
          <div class="table-responsive mt-5">
            <h5>
              Mes factures établie
            </h5>
              <table id="zero_config3" class="table table-striped table-bordered text-nowrap align-middle">
                <thead>
                  <tr>
                    <th>Date</th>

                      <th>N° Proforma</th>
                      <th>Client</th>
                      <th>Coût</th>
                      <th>Statut</th>
                      <th>Action</th>

                  </tr>
                </thead>
                <tbody>
                  @forelse ($mes_factures as $facture)
                  <tr>
                    <td>
                      <h6 class="mb-0">{{ $facture->created_at }}</h6>
                  </td>
                      <td>
                          <h6 class="mb-0">{{ $facture->numero }}</h6>
                      </td>
                     <td>
                        {{ $facture->devis->client->nom }}
                     </td>
                      <td>{{ $facture->devis->details->sum('total') }} {{ $facture->devis->devise }}</td>
                      <td>{{ $facture->devis->status ?? 'Non renseigné' }}</td>
  
                      <td>
                        <a href="{{ route('dashboard.factures.download', $facture->id) }}" class="text-primary me-2" title="Télécharger">
                          <i class="ti ti-download fs-5"></i>
                        </a>
                        
                      </td>
                    
                  </tr>
  
  
                  @empty
                      Aucune Facture enregistrée.
                  @endforelse
                  
              </tbody>
              
                <tfoot>
                  <tr>
                    <th>Date</th>

                      <th>N° Proforma</th>
                      <th>Client</th>
                      <th>Coût</th>
                      <th>Statut</th>
                      <th>Action</th>

                  </tr>
                </tfoot>
              </table>
              
          </div>
        </div>
      </div>

</div>
@endsection

@push('scripts')

@if(session('pdf_path'))
    <script>
        window.onload = function() {
            let link = document.createElement('a');
            link.href = "{{ asset('storage/' . session('pdf_path')) }}";
            link.download = "{{ basename(session('pdf_path')) }}";
            link.click();
        }
    </script>
@endif

<script>
  function confirmDelete(devisId) {
      Swal.fire({
          title: "Êtes-vous sûr ?",
          text: "Cette action est irréversible !",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Oui, supprimer !",
          cancelButtonText: "Annuler"
      }).then((result) => {
          if (result.isConfirmed) {
              document.getElementById('delete-form-' + devisId).submit();
          }
      });
  }
</script>

@endpush

