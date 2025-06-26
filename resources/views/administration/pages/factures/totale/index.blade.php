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
    
            {{-- <a href="" class="btn btn-primary">
                <i class="ti ti-users text-white me-1 fs-5"></i> 
                Faire une facture
            </a> --}}
              
          </div>
        </div>
      </div>

     @if(Auth::user()->hasRole('Commercial'))
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
                    <th>Montant</th>
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
                      {{ $factureCommercial->status ?? 'Non renseigné' }}
                  
                      <!-- Afficher l'icône si le statut est "Réfusé" -->
                      @if($factureCommercial->status === 'Réfusé')
                          <!-- Icône d'œil pour ouvrir le modal -->
                          <i class="ti ti-eye" data-bs-toggle="modal" data-bs-target="#refusModal{{ $factureCommercial->id }}"></i>
                      @endif
                      
                  </td>
                  
                  <!-- Modal pour afficher le message de refus -->
                  <div class="modal fade" id="refusModal{{ $factureCommercial->id }}" tabindex="-1" aria-labelledby="refusModalLabel{{ $factureCommercial->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="refusModalLabel{{ $factureCommercial->id }}">Message de Refus</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  {{ $factureCommercial->message ?? 'Aucun message fourni.' }}
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                              </div>
                          </div>
                      </div>
                  </div>

                  
                  
                    
                      <td>
                          
                          <a href="{{ route('dashboard.factures.totales.download', $factureCommercial->id) }}" class="text-primary me-2" title="Télécharger">
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
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
              </tfoot>
            </table>
        </div>

      
      </div>
      
     @endif
      

     @if(Auth::user()->hasAnyRole(['Daf', 'Comptable', 'DG']))
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
                    @if(Auth::user()->hasRole(['Daf','DG']))
                    <th>Pays</th>

                    @endif
                    <th>Etabli Par</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
              </thead>
                @if (Auth::user()->hasRole(['Daf','DG']))
                <tbody>
                  @forelse ($all_devis as $devi)
                  <tr>
                    <td>
                      <h6 class="mb-0">{{ $devi->created_at }}</h6>
                    </td>
                    <td>
                      <h6 class="mb-0">{{ $devi->num_proforma }}</h6>
                    </td>
                    @if(Auth::user()->hasRole(['Daf','DG']))
                    <td>
                      <h6 class="mb-0">{{ $devi->pays->name }}</h6>
                    </td>

                    @endif
                    <td>{{ $devi->user->name }}</td>
                      <td>{{ $devi->client->nom }}</td>
                      <td>{{ $devi->total_ttc }} {{ $devi->devise }}</td>
                     

                      <td>
                        {{ $devi->status ?? 'Non renseigné' }}
                        <!-- Vérifier si la facture existe avant d'accéder au statut -->
                        @if($devi->facture && $devi->facture->status === 'Réfusé')
                            <!-- Icône d'œil pour ouvrir le modal -->
                            <i class="ti ti-eye" data-bs-toggle="modal" data-bs-target="#refusModal{{ $devi->facture->id }}"></i>

                             <!-- Modal pour afficher le message de refus -->
                            <div class="modal fade" id="refusModal{{ $devi->facture->id }}" tabindex="-1" aria-labelledby="refusModalLabel{{ $devi->facture->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title" id="refusModalLabel{{ $devi->facture->id }}">Message de Refus de facture</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                          {{ $devi->facture->message ?? 'Aucun message fourni.' }}
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        @endif
                    </td>
  
                      <td>

                        @if(Auth::user()->hasRole(['Daf', 'DG']))
                          @if ($devi->facture) 
                            <a href="{{ route('dashboard.factures.totales.download', $devi->facture->id) }}" class="text-primary me-2" title="Télécharger la facture">
                              <i class="ti ti-download fs-5"></i>
                            </a>
                          @else
                              
                          @endif
                        @else
                          <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger la proforma">
                            <i class="ti ti-download fs-5"></i>
                          </a>
                        @endif
                       
                          
                          <a href="{{ route('dashboard.factures.totales.create', $devi->id) }}" class="text-success me-2" title="Etablir la facture">
                            <i class="ti ti-clipboard-list"></i>
                        </a>
                        
                        @if ($devi->facture) 
                            <a href="{{ route('dashboard.factures.totales.validate', $devi->facture->id) }}" class="text-success me-2" title="Approuver la facture">
                                <i class="ti ti-copy-check"></i>
                            </a>
                        @else
                            
                        @endif



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

                      
                      <td>
                        {{ $devi->status ?? 'Non renseigné' }}
                        <!-- Vérifier si la facture existe avant d'accéder au statut -->
                        @if($devi->facture && $devi->facture->status === 'Réfusé')
                            <!-- Icône d'œil pour ouvrir le modal -->
                            <i class="ti ti-eye" data-bs-toggle="modal" data-bs-target="#refusModal{{ $devi->facture->id }}"></i>

                             <!-- Modal pour afficher le message de refus -->
                            <div class="modal fade" id="refusModal{{ $devi->facture->id }}" tabindex="-1" aria-labelledby="refusModalLabel{{ $devi->facture->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title" id="refusModalLabel{{ $devi->facture->id }}">Message de Refus de facture</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                          {{ $devi->facture->message ?? 'Aucun message fourni.' }}
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        @endif
                    </td>

                    
                    
                      <td>
                        <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger">
                          <i class="ti ti-download fs-5"></i>
                        </a>
                        
                          
                          <a href="{{ route('dashboard.factures.totales.create', $devi->id) }}" class="text-success me-2" title="Etablir la facture">
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
                    @if(Auth::user()->hasRole(['Daf','DG']))
                    <th>Pays</th>

                    @endif
                    <th>Etabli Par</th>
                    <th>Client</th>
                    <th>Montant</th>
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
            <div>
              <h5>
                Historiques des factures
              </h5>
              <form method="POST" action="{{ route('dashboard.factures.totales.index') }}">
                @csrf
                <div class="d-flex">
                 @if(Auth::user()->hasRole(['Daf','DG']))
                 <select name="pays" id="filter-pays" class="select2 form-control custom-select">
                  <option value="">Tous les pays</option>
                  @foreach ($payss as $pays )
                  <option value="{{ $pays->id }}" {{ request('pays') == $pays->id ? 'selected' : '' }}>
                    {{ $pays->name }}
                  </option>

                  @endforeach
                 
              </select>
                 @endif
                    <select name="my" id="filter-my" class="select2 form-control custom-select">
                      <option value="">
                        Toutes les factures
                      </option>
                      <option value="{{ Auth::user()->id }}" {{ request('my') == Auth::user()->id ? 'selected' : '' }}>
                        Mes factures
                      </option>
                    </select>
            
                    <div class="input-daterange input-group mr-3" id="date-range">
                        <input type="date" name="start" id="start-date" class="form-control mydatepicker" value="{{ request('start') }}">
                        <span class="input-group-text bg-primary b-0 text-white">A</span>
                        <input type="date" name="end" id="end-date" class="form-control mydatepicker" value="{{ request('end') }}">
                    </div>
            
                    <button type="submit" id="filter-button" class="btn btn-primary">
                        Appliquer
                    </button>
            
                   
                </div>
            </form>
            </div>
              <table id="zero_config2" class="table table-striped table-bordered text-nowrap align-middle">
                <thead>
                  <tr>
                    <th>Date</th>

                      <th>N° Facture</th>

                      @if(Auth::user()->hasRole(['Daf','DG']))
                        <th>Pays</th>
                      @endif
                      <th>Etabli Par</th>
                      <th>Client</th>
                      <th>Montant</th>
                      <th>Statut</th>
                      <th>Action</th>

                  </tr>
                </thead>

                @if(Auth::user()->hasRole(['Daf', 'Comptable', 'DG']))
                  <tbody>
                    @forelse ($all_factures as $facture)
                    <tr>
                      <td>
                        <h6 class="mb-0">{{ $facture->created_at }}</h6>
                      </td>
                        {{-- <td>
                            <h6 class="mb-0">{{ $facture->numero }}</h6>
                        </td> --}}
                        <td>
                          @php
                          $montant_total = $facture->devis->total_ttc;
                          $montant_solde = $facture->montant_solde;

                          if ($montant_solde == 0) {
                              $status_solde = 'Non renseigné';
                              $color = 'text-muted';
                          } elseif ($montant_solde >= $montant_total) {
                              $status_solde = 'Soldé';
                              $color = 'text-success';
                          } else {
                              $status_solde = 'Partiel';
                              $color = 'text-warning';
                          }

                          $status_facture = $facture->devis->status ?? 'Non renseigné';
                      @endphp

                          <h6 class="mb-0 {{ $color }}">{{ $facture->numero }}</h6>
                      </td>
                      
                      
                        @if(Auth::user()->hasRole(['Daf', 'DG']))
                        <td>
                          <h6 class="mb-0">{{ $facture->devis->pays->name }}</h6>
                      </td>
                        @endif
                      
                      <td>
                        <h6 class="mb-0">{{ $facture->user->name }}</h6>
                      </td>
                      <td>
                          {{ $facture->devis->client->nom }}
                      </td>
                        <td>{{ $facture->devis->total_ttc }} {{ $facture->devis->devise }}</td>
                        @php
                          $montant_total = $facture->devis->total_ttc;
                          $montant_solde = $facture->montant_solde;

                          if ($montant_solde == 0) {
                              $status_solde = 'Non Payé';
                              $color = 'text-muted';
                          } elseif ($montant_solde >= $montant_total) {
                              $status_solde = 'Soldé';
                              $color = 'text-success';
                          } else {
                              $status_solde = 'Partiel';
                              $color = 'text-warning';
                          }

                          $status_facture = $facture->devis->status ?? 'Non renseigné';
                      @endphp

                      <td>
                          <span class="text-primary">{{ $status_facture }}</span> |
                          <span class="{{ $color }}">{{ $status_solde }}</span>
                      </td>


    
                        <td>
                          <a href="{{ route('dashboard.factures.totales.download', $facture->id) }}" class="text-primary me-2" title="Télécharger">
                            <i class="ti ti-download fs-5"></i>
                          </a>
                          

                          <a href="#" class="text-info me-2" data-bs-toggle="modal" data-bs-target="#updateSoldeModal-{{ $facture->id }}" title="Mettre à jour le montant soldé">
                            <i class="ti ti-currency-dollar fs-5"></i>
                          </a>
                        

                          <!-- Modal -->
                          <div class="modal fade" id="updateSoldeModal-{{ $facture->id }}" tabindex="-1" aria-labelledby="updateSoldeLabel-{{ $facture->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                              <form action="{{ route('dashboard.factures.totales.updateSolde', $facture->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title">Mettre à jour le montant soldé</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="mb-3">
                                      <label for="montant_solde_{{ $facture->id }}" class="form-label">Montant soldé</label>
                                      <input type="number" step="0.01" min="0" class="form-control" name="montant_solde" id="montant_solde_{{ $facture->id }}" value="{{ $facture->montant_solde }}" required>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                  </div>
                                </div>
                              </form>
                            </div>
                          </div>
                          
                          
                        </td>
                      
                    </tr>
    
                    @empty
                        
                    @endforelse
                    
                  </tbody>
                @endif
              
                <tfoot>
                  <tr>
                    <th>Date</th>

                      <th>N° Facture</th>
                      @if(Auth::user()->hasRole(['Daf', 'DG']))
                        <th>Pays</th>
                      @endif
                      <th>Etabli Par</th>
                      <th>Client</th>
                      <th>Montant</th>
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
     
      {{-- <div class="card card-body">
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
                      <th>Montant</th>
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
                        <a href="{{ route('dashboard.factures.totales.download', $facture->id) }}" class="text-primary me-2" title="Télécharger">
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
                      <th>Montant</th>
                      <th>Statut</th>
                      <th>Action</th>

                  </tr>
                </tfoot>
              </table>
              
          </div>
        </div>
      </div> --}}

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

