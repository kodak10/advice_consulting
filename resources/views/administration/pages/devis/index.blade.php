@extends('administration.layouts.master')

@section('content')
<div class="container-fluid">
           
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Mes Proformas</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a class="text-muted text-decoration-none" href="{{ route('dashboard.') }}">Accueil</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Mes Proformas</li>
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

                @if($errors->any())
                  <div class="alert alert-danger">
                      <ul>
                          @foreach($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
                @endif

                  {{-- @if (session('gmailUrl'))
                  <div class="alert alert-success">
                      <p>✅ La Proforma a été approuvé avec succès.</p>
                      <button class="btn btn-primary" onclick="openGmail()">📧 Ouvrir Gmail</button>
                  </div>

                  <script>
                      function openGmail() {
                          window.open("{{ session('gmailUrl') }}", "_blank");
                      }

                      // Ouvrir Gmail automatiquement après 1 seconde
                      setTimeout(() => {
                          window.open("{{ session('gmailUrl') }}", "_blank");
                      }, 1000);
                  </script>
              @endif --}}

            </div>
          <div class="col-md-4 col-xl-9 text-end d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
    
            <a href="{{ route('dashboard.devis.create') }}" class="btn btn-primary">
                <i class="ti ti-users text-white me-1 fs-5"></i> 
                Faire une Proforma
            </a>
              
          </div>
        </div>
      </div>

      @if (Auth::user()->hasRole('Commercial'))
            <h5>Mes Proformas</h5>
            <table id="zero_config1" class="table table-striped table-bordered text-nowrap align-middle">
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
                @forelse ($mes_devis as $devi)
                <tr>
                  <td>
                    <h6 class="mb-0">{{ $devi->created_at }}</h6>
                  </td>
                    <td>
                        <h6 class="mb-0">{{ $devi->num_proforma }}</h6>
                    </td>
                    <td>{{ $devi->client->nom }}</td>
                    <td>{{ $devi->total_ttc }} {{ $devi->devise }}</td>


                    <td>
                      {{ $devi->status ?? 'Non renseigné' }}
                  
                      <!-- Afficher l'icône si le statut est "Réfusé" -->
                      @if($devi->status === 'Réfusé')
                          <!-- Icône d'œil pour ouvrir le modal -->
                          <i class="ti ti-eye" data-bs-toggle="modal" data-bs-target="#refusModal{{ $devi->id }}"></i>
                      @endif
                  </td>
                  
                  <!-- Modal pour afficher le message de refus -->
                  <div class="modal fade" id="refusModal{{ $devi->id }}" tabindex="-1" aria-labelledby="refusModalLabel{{ $devi->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="refusModalLabel{{ $devi->id }}">Message de Refus</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  {{ $devi->message ?? 'Aucun message fourni.' }}
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                              </div>
                          </div>
                      </div>
                  </div>

                    <td>
                      <div class="action-btn text-center">
                        <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger">
                          <i class="ti ti-download fs-5"></i>
                        </a>

                      <a href="{{ route('dashboard.devis.validate', $devi->id) }}" class="text-success me-2" title="Valider">
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
      @endif
      
      @if (Auth::user()->hasRole('Comptable'))
     
        <div class="card card-body">
          <h5>Liste des Proformas</h5>
      
          <div class="table-responsive">
              <table id="zero_config2" class="table table-striped table-bordered text-nowrap align-middle">
                  <thead>
                      <tr>
                          <th>Date</th>
                          <th>N° Proforma</th>
                          <th>Client</th>
                          <th>Montant</th>
                          <th>Etabli Par</th>
                          <th>Statut</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      @forelse ($devis as $devi)
                      <tr>
                          <td><h6 class="mb-0">{{ $devi->created_at }}</h6></td>
                          <td><h6 class="mb-0">{{ $devi->num_proforma }}</h6></td>
                          <td>{{ $devi->client->nom }}</td>
                          <td>{{ $devi->total_ttc }} {{ $devi->devise }}</td>
                          <td>{{ $devi->user->name }}</td>
      
                          <td>
                              {{ $devi->status ?? 'Non renseigné' }}
      
                              <!-- Afficher l'icône si le statut est "Réfusé" -->
                              @if($devi->status === 'Réfusé')
                                  <!-- Icône d'œil pour ouvrir le modal -->
                                  <i class="ti ti-eye" data-bs-toggle="modal" data-bs-target="#refusModal{{ $devi->id }}"></i>
                              @endif
                          </td>
      
                          <!-- Modal pour afficher le message de refus -->
                          <div class="modal fade" id="refusModal{{ $devi->id }}" tabindex="-1" aria-labelledby="refusModalLabel{{ $devi->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <h5 class="modal-title" id="refusModalLabel{{ $devi->id }}">Message de Refus</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                      </div>
                                      <div class="modal-body">
                                          {{ $devi->message ?? 'Aucun message fourni.' }}
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
      
                          <td>
                              <div class="action-btn text-center">
                                  <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger">
                                      <i class="ti ti-download fs-5"></i>
                                  </a>
      
                                  <!-- Lien pour afficher le modal de refus -->
                                  <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#refuseModal{{ $devi->id }}">
                                      <i class="ti ti-square-rounded-minus"></i>
                                  </a>
      
                                  <!-- Modal pour saisir un message de refus -->
                                  <div class="modal fade" id="refuseModal{{ $devi->id }}" tabindex="-1" aria-labelledby="refuseModalLabel{{ $devi->id }}" aria-hidden="true">
                                      <div class="modal-dialog">
                                          <div class="modal-content">
                                              <div class="modal-header">
                                                  <h5 class="modal-title" id="refuseModalLabel{{ $devi->id }}">Motif du refus de la proforma</h5>
                                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                              </div>
                                              <div class="modal-body">
                                                  <form method="POST" action="{{ route('dashboard.devis.refuse', $devi->id) }}">
                                                      @csrf
                                                      <div class="mb-3">
                                                          <label for="refuse_message" class="form-label">Message de refus</label>
                                                          <textarea class="form-control" id="refuse_message" name="message" rows="4" required></textarea>
                                                      </div>
                                                      <div class="modal-footer">
                                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                          <button type="submit" class="btn btn-danger">Envoyer</button>
                                                      </div>
                                                  </form>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
      
                              </div>
                          </td>
                      </tr>
                      @empty
                      <tr>
                          <td colspan="7" class="text-center">Aucune Proforma enregistrée.</td>
                      </tr>
                      @endforelse
                  </tbody>
                  <tfoot>
                      <tr>
                          <th>Date</th>
                          <th>N° Proforma</th>
                          <th>Client</th>
                          <th>Montant</th>
                          <th>Etabli Par</th>
                          <th>Statut</th>
                          <th>Action</th>
                      </tr>
                  </tfoot>
              </table>
          </div>
        </div>
      

        <div class="card card-body">
          <h5>Historiques</h5>
          <div class="table-responsive">
            <table id="zero_config3" class="mt-5 table table-striped table-bordered text-nowrap align-middle">
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
                @forelse ($mes_devis as $devi)
                <tr>
                  <td>
                    <h6 class="mb-0">{{ $devi->created_at }}</h6>
                </td>
                    <td>
                        <h6 class="mb-0">{{ $devi->num_proforma }}</h6>
                    </td>
                    <td>{{ $devi->client->nom }}</td>
                    <td>{{ $devi->total_ttc }} {{ $devi->devise }}</td>
                    <td>
                      {{ $devi->status ?? 'Non renseigné' }}
                  
                      <!-- Afficher l'icône si le statut est "Réfusé" -->
                      @if($devi->status === 'Réfusé')
                          <!-- Icône d'œil pour ouvrir le modal -->
                          <i class="ti ti-eye" data-bs-toggle="modal" data-bs-target="#refusModal{{ $devi->id }}"></i>
                      @endif
                  </td>
                  
                  <!-- Modal pour afficher le message de refus -->
                  <div class="modal fade" id="refusModal{{ $devi->id }}" tabindex="-1" aria-labelledby="refusModalLabel{{ $devi->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="refusModalLabel{{ $devi->id }}">Message de Refus</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  {{ $devi->message ?? 'Aucun message fourni.' }}
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                              </div>
                          </div>
                      </div>
                  </div>

                    <td>
                      <div class="action-btn text-center">
                        <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger">
                          <i class="ti ti-download fs-5"></i>
                        </a>
                      

                      <a href="{{ route('dashboard.devis.validate', $devi->id) }}" class="text-success me-2" title="Valider">
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



      @if (Auth::user()->hasRole('DG'))
        <div class="card card-body">
          <h5>Liste des Proformas</h5>

          <div class="table-responsive">

            <table id="zero_config2" class="table table-striped table-bordered text-nowrap align-middle">
              <thead>
                <tr>
                  <th>Date</th>

                    <th>N° Proforma</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Etabli Par</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($devisAlls as $devi)
                <tr>
                    <td>
                      <h6 class="mb-0">{{ $devi->created_at }}</h6>
                    </td>
                    <td>
                        <h6 class="mb-0">{{ $devi->num_proforma }}</h6>
                    </td>
                    <td>{{ $devi->client->nom }}</td>
                    <td>{{ $devi->total_ttc }} {{ $devi->devise }}</td>
                    <td>{{ $devi->user->name }}</td>
                    <td>
                      {{ $devi->status ?? 'Non renseigné' }}
                  
                      <!-- Afficher l'icône si le statut est "Réfusé" -->
                      @if($devi->status === 'Réfusé')
                          <!-- Icône d'œil pour ouvrir le modal -->
                          <i class="ti ti-eye" data-bs-toggle="modal" data-bs-target="#refusModal{{ $devi->id }}"></i>
                      @endif
                  </td>
                  
                  <!-- Modal pour afficher le message de refus -->
                  <div class="modal fade" id="refusModal{{ $devi->id }}" tabindex="-1" aria-labelledby="refusModalLabel{{ $devi->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="refusModalLabel{{ $devi->id }}">Message de Refus</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  {{ $devi->message ?? 'Aucun message fourni.' }}
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                              </div>
                          </div>
                      </div>
                  </div>

                  <td>
                    <div class="action-btn text-center">
                        <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger">
                            <i class="ti ti-download fs-5"></i>
                        </a>

                        <!-- Lien pour afficher le modal de refus -->
                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#refuseModal{{ $devi->id }}">
                            <i class="ti ti-square-rounded-minus"></i>
                        </a>

                        <!-- Modal pour saisir un message de refus -->
                        <div class="modal fade" id="refuseModal{{ $devi->id }}" tabindex="-1" aria-labelledby="refuseModalLabel{{ $devi->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="refuseModalLabel{{ $devi->id }}">Motif du refus de la proforma</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="{{ route('dashboard.devis.refuse', $devi->id) }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="refuse_message" class="form-label">Message de refus</label>
                                                <textarea class="form-control" id="refuse_message" name="message" rows="4" required></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                <button type="submit" class="btn btn-danger">Envoyer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </td>
                    
                  
                </tr>

                @empty
                    Aucune Proforma enregistrée.
                @endforelse
                
            </tbody>
            
              <tfoot>
                <tr>
                  <th>Date</th>

                    <th>N° Proforma</th>
                    <th>Client</th>
                    <th>Montant</th>
                    <th>Etabli Par</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
              </tfoot>
            </table>


          </div>
        </div>

        <div class="card card-body">
          <h5>Historiques</h5>
          <div class="table-responsive">
            <table id="zero_config3" class="mt-5 table table-striped table-bordered text-nowrap align-middle">
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
                @forelse ($mes_devis as $devi)
                <tr>
                  <td>
                    <h6 class="mb-0">{{ $devi->created_at }}</h6>
                </td>
                    <td>
                        <h6 class="mb-0">{{ $devi->num_proforma }}</h6>
                    </td>
                    <td>{{ $devi->client->nom }}</td>
                    <td>{{ $devi->total_ttc }} {{ $devi->devise }}</td>
                    <td>
                      {{ $devi->status ?? 'Non renseigné' }}
                  
                      <!-- Afficher l'icône si le statut est "Réfusé" -->
                      @if($devi->status === 'Réfusé')
                          <!-- Icône d'œil pour ouvrir le modal -->
                          <i class="ti ti-eye" data-bs-toggle="modal" data-bs-target="#refusModal{{ $devi->id }}"></i>
                      @endif
                  </td>
                  
                  <!-- Modal pour afficher le message de refus -->
                  <div class="modal fade" id="refusModal{{ $devi->id }}" tabindex="-1" aria-labelledby="refusModalLabel{{ $devi->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="refusModalLabel{{ $devi->id }}">Message de Refus</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                                  {{ $devi->message ?? 'Aucun message fourni.' }}
                              </div>
                              <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                              </div>
                          </div>
                      </div>
                  </div>
                    <td>
                      <div class="action-btn text-center">
                        <a href="{{ route('dashboard.devis.download', $devi->id) }}" class="text-primary me-2" title="Télécharger">
                          <i class="ti ti-download fs-5"></i>
                        </a>
                      

                      <a href="{{ route('dashboard.devis.validate', $devi->id) }}" class="text-success me-2" title="Valider">
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

    </div>
  
</div>
@endsection

@push('scripts')
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    </script>
@endif

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

