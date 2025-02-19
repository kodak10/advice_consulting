@extends('administration.layouts.master')

@section('content')
<div class="container-fluid">
           
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Liste des banques</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a class="text-muted text-decoration-none" href="{{ route('dashboard.') }}">Accueil</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Liste des banques</li>
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
            

           

            <button type="button" class="btn bg-warning-subtle text-warning px-4 fs-4 " data-bs-toggle="modal" data-bs-target="#addBanqueModal">
                <i class="ti ti-users text-white me-1 fs-5"></i> 
                Ajouter une banque
              </button>
              
          </div>
        </div>
      </div>

      <!-- Modal -->
      <!-- Modal for adding banques -->
    <div class="modal fade" id="addBanqueModal" tabindex="-1" role="dialog" aria-labelledby="addBanqueModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">Information sur la banque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(session('error'))
                    <div class="alert alert-danger text-danger" role="alert">
                        {!! session('error') !!}
                    </div>
                @endif


                <div class="add-contact-box">
                    <div class="add-contact-content">
                    <form id="addBanqueModalTitle">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 contact-name">
                                <input type="text" id="name" class="form-control"value="" placeholder="Nom ou raison sociale">
                                <span class="validation-text text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 contact-occupation">
                                    <input type="text" id="numero_compte"  class="form-control" placeholder="0">
                                </div>
                            </div>
                            
                        </div>

                    </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <div class="d-flex gap-6 m-0">
                <button id="btn-add" class="btn btn-success">Ajouter</button>
                <button class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal"> Annuler</button>
            </div>
            </div>
        </div>
        </div>
    </div>


      <div class="card card-body">
        <div class="table-responsive">
            <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
              <thead>
                <!-- start row -->
                <tr>
                  <th>Nom</th>
                  <th>Numéro de compte</th>
                  <th>Action</th>
                </tr>
                <!-- end row -->
              </thead>
              <tbody>
                @forelse ($banques as $banque)
                <tr>
                    <td>
                        <h6 class="mb-0">{{ $banque->name }}</h6>
                    </td>
                    <td>{{ $banque->num_compte }}</td>
                    <td>
                        <div class="action-btn text-center">
                            <a href="#editBanqueModal{{ $banque->id }}" class="text-primary edit" title="Modifier" data-bs-toggle="modal">
                                <i class="ti ti-pencil fs-5"></i> 
                            </a>

                            <form id="delete-form-{{ $banque->id }}" action="{{ route('dashboard.banques.destroy', $banque->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-dark delete ms-2" title="Supprimer" style="border: none; background: none;" onclick="confirmDelete({{ $banque->id }})">
                                    <i class="ti ti-trash fs-5"></i>
                                </button>
                            </form>
                            
                            
                        </div>
                    </td>
                </tr>


                <!-- Modal de modification -->
                <div class="modal fade" id="editBanqueModal{{ $banque->id }}" tabindex="-1" aria-labelledby="editBanqueModalLabel{{ $banque->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h5 class="modal-title">Modifier les informations de la banque</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Affichage des messages d'erreur et de succès -->
                                <div id="error-message{{ $banque->id }}" class="alert alert-danger text-danger" style="display: none;"></div>
                                <div id="success-message{{ $banque->id }}" class="alert alert-success text-success" style="display: none;"></div>

                                <div class="add-contact-box">
                                    <div class="add-contact-content">
                                        <form id="update-banque-form{{ $banque->id }}">
                                            @csrf
                                            @method('PUT')

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3 contact-name">
                                                        <input type="text" id="c-name{{ $banque->id }}" class="form-control" placeholder="Nom ou raison sociale" name="name" value="{{ $banque->name }}">
                                                        <span class="validation-text text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Autres champs du formulaire -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3 contact-occupation">
                                                        <input type="text" id="c-occupation{{ $banque->id }}" class="form-control" placeholder="Numéro de compte" name="num_compte" value="{{ $banque->num_compte }}">
                                                    </div>
                                                </div>
                                                
                                            </div>


                                            <div class="modal-footer">
                                                <div class="d-flex gap-6 m-0">
                                                    <button type="submit" class="btn btn-success">Sauvegarder</button>
                                                    <button class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal"> Annuler</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                    Aucune Banque enregistré.
                @endforelse
                
            </tbody>
            
              <tfoot>
                <!-- start row -->
                <tr>
                    <th>Nom</th>
                    <th>Numéro de compte</th>
                    <th>Action</th>
                </tr>
                <!-- end row -->
              </tfoot>
            </table>
          </div>
      </div>
    </div>
  

</div>
@endsection

@push('scripts')
<script>
  document.getElementById('btn-add').addEventListener('click', function(event) {
    event.preventDefault();

    // Récupérer les données du formulaire
    let formData = {
        name: document.getElementById('name').value,
        num_compte: document.getElementById('numero_compte').value,
    };
    console.log("Numéro de compte:", formData.numero_compte);
    // Envoi de la requête avec fetch
    fetch("{{ route('dashboard.banques.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher le toast de succès
            toastr.success(data.message, 'Succès', {
                positionClass: 'toast-top-right',
                timeOut: 5000,
                closeButton: true,
                progressBar: true,
            });

            // Fermer le modal et recharger la page après un délai
            $('#addBanqueModal').modal('hide');
            setTimeout(() => {
                location.reload();  // Recharger la page pour afficher les nouvelles banques
            }, 3000);
        } else {
            // Afficher le toast d'erreur lorsque data.success est false
            toastr.error(data.message, 'Erreur', {
                positionClass: 'toast-top-right',
                timeOut: 20000,
                closeButton: true,
                progressBar: true,
            });
            // Vous pouvez également choisir de recharger la page ou non
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        // En cas d'erreur réseau ou autre, afficher un toast d'erreur générique
        toastr.error("Une erreur est survenue lors de l'ajout d'une banque.", 'Erreur', {
            positionClass: 'toast-top-right',
            timeOut: 20000,
            closeButton: true,
            progressBar: true,
        });
        setTimeout(() => {
            location.reload();
        }, 1000);

    });
});

</script>

@if($banques->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach($banques as $banque)
                document.getElementById('update-banque-form{{ $banque->id }}').addEventListener('submit', function(event) {
                    event.preventDefault();

                    let formData = new FormData(this);

                    fetch("{{ route('dashboard.banques.update', ['banque' => $banque->id]) }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message, 'Succès', {
                                positionClass: 'toast-top-right',
                                timeOut: 5000,
                                closeButton: true,
                                progressBar: true,
                            });

                            $('#editBanqueModal{{ $banque->id }}').modal('hide');
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            toastr.error(data.message, 'Erreur', {
                                positionClass: 'toast-top-right',
                                timeOut: 20000,
                                closeButton: true,
                                progressBar: true,
                            });

                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        toastr.error("Une erreur est survenue lors de la mise à jour.", 'Erreur', {
                            positionClass: 'toast-top-right',
                            timeOut: 20000,
                            closeButton: true,
                            progressBar: true,
                        });

                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    });
                });
            @endforeach
        });
    </script>
@endif


<script>
    function confirmDelete(banqueId) {
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
                document.getElementById('delete-form-' + banqueId).submit();
            }
        });
    }
</script>



@endpush

