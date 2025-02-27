@extends('administration.layouts.master')

@section('content')
<div class="container-fluid">
           
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Compte Clients</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a class="text-muted text-decoration-none" href="{{ route('dashboard.') }}">Accueil</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Compte Clients</li>
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
            
            <button type="button" class="btn bg-warning-subtle text-warning px-4 fs-4 " data-bs-toggle="modal" data-bs-target="#addContactModal">
                <i class="ti ti-users text-white me-1 fs-5"></i> 
                Ajouter un Client
              </button>
              
          </div>
        </div>
      </div>

      <!-- Modal -->
      <!-- Modal for adding client -->
    <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">Information du Client</h5>
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
                    <form id="addContactModalTitle">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 contact-name">
                                <input type="text" id="c-name" class="form-control" placeholder="Nom ou raison sociale">
                                <span class="validation-text text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 contact-occupation">
                                <input type="text" id="c-occupation" class="form-control" placeholder="N°CC">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 contact-phone">
                                <input type="text" id="c-phone" class="form-control" placeholder="Téléphone">
                                <span class="validation-text text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 contact-occupation">
                            <input type="text" id="c-adresse" class="form-control" placeholder="Adresse">
                            </div>
                        </div>
                        </div>

                        <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 contact-phone">
                            <input type="text" id="c-ville" class="form-control" placeholder="Ville">
                            <span class="validation-text text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 contact-phone">
                            <input type="text" id="c-attn" class="form-control" placeholder="ATTM">
                            <span class="validation-text text-danger"></span>
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
                <tr>
                  <th>Nom</th>
                  <th>N° Téléphone</th>
                  <th>Adresse</th>
                  <th>N°CC</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($clients as $client)
                <tr>
                    <td>
                        <h6 class="mb-0">{{ $client->nom }}</h6>
                    </td>
                    <td>{{ $client->telephone }}</td>
                    <td>{{ $client->ville }}</td>
                    <td>{{ $client->numero_cc ?? 'Non renseigné' }}</td>
                    <td>
                        <div class="action-btn text-center">
                            <a href="#editClientModal{{ $client->id }}" class="text-primary edit" title="Modifier" data-bs-toggle="modal">
                                <i class="ti ti-pencil fs-5"></i> 
                            </a>
                            
                            <form id="delete-form-{{ $client->id }}" action="{{ route('dashboard.clients.destroy', $client->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-dark delete ms-2" title="Supprimer" style="border: none; background: none;" onclick="confirmDelete({{ $client->id }})">
                                    <i class="ti ti-trash fs-5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>


                <!-- Modal de modification -->
                <div class="modal fade" id="editClientModal{{ $client->id }}" tabindex="-1" aria-labelledby="editClientModalLabel{{ $client->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h5 class="modal-title">Modifier les informations du Client</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Affichage des messages d'erreur et de succès -->
                                <div id="error-message{{ $client->id }}" class="alert alert-danger text-danger" style="display: none;"></div>
                                <div id="success-message{{ $client->id }}" class="alert alert-success text-success" style="display: none;"></div>

                                <div class="add-contact-box">
                                    <div class="add-contact-content">
                                        <form id="update-client-form{{ $client->id }}">
                                            @csrf
                                            @method('PUT')

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3 contact-name">
                                                        <input type="text" id="c-name{{ $client->id }}" class="form-control" placeholder="Nom ou raison sociale" name="nom" value="{{ $client->nom }}">
                                                        <span class="validation-text text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3 contact-occupation">
                                                        <input type="text" id="c-occupation{{ $client->id }}" class="form-control" placeholder="N°CC" name="numero_cc" value="{{ $client->numero_cc }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 contact-phone">
                                                        <input type="text" id="c-phone{{ $client->id }}" class="form-control" placeholder="Téléphone" name="telephone" value="{{ $client->telephone }}">
                                                        <span class="validation-text text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="mb-3 contact-occupation">
                                                        <input type="text" id="c-adresse{{ $client->id }}" class="form-control" placeholder="Adresse" name="adresse" value="{{ $client->adresse }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3 contact-phone">
                                                        <input type="text" id="c-ville{{ $client->id }}" class="form-control" placeholder="Ville" name="ville" value="{{ $client->ville }}">
                                                        <span class="validation-text text-danger"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 contact-phone">
                                                        <input type="text" id="c-attn{{ $client->id }}" class="form-control" placeholder="ATTM" name="attn" value="{{ $client->attn }}">
                                                        <span class="validation-text text-danger"></span>
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
                    Aucun client enregistré.
                @endforelse
                
            </tbody>
            
              <tfoot>
                <tr>
                    <th>Nom</th>
                    <th>N° Téléphone</th>
                    <th>Adresse</th>
                    <th>N°CC</th>
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
<script>
  document.getElementById('btn-add').addEventListener('click', function(event) {
    event.preventDefault();

    let formData = {
        nom: document.getElementById('c-name').value,
        numero_cc: document.getElementById('c-occupation').value,
        telephone: document.getElementById('c-phone').value,
        adresse: document.getElementById('c-adresse').value,
        ville: document.getElementById('c-ville').value,
        attn: document.getElementById('c-attn').value,
    };

    fetch("{{ route('dashboard.clients.store') }}", {
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
            toastr.success(data.message, 'Succès', {
                positionClass: 'toast-top-right',
                timeOut: 5000,
                closeButton: true,
                progressBar: true,
            });

            $('#addContactModal').modal('hide');
            setTimeout(() => {
                location.reload();  
            }, 5000);
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
        toastr.error("Une erreur est survenue lors de l'ajout du client.", 'Erreur', {
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

@if($clients->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach($clients as $client)
                document.getElementById('update-client-form{{ $client->id }}').addEventListener('submit', function(event) {
                    event.preventDefault();

                    let formData = new FormData(this);

                    fetch("{{ route('dashboard.clients.update', ['client' => $client->id]) }}", {
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

                            $('#editClientModal{{ $client->id }}').modal('hide');
                            setTimeout(() => {
                                location.reload();
                            }, 5000);
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
    function confirmDelete(clientId) {
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
                document.getElementById('delete-form-' + clientId).submit();
            }
        });
    }
</script>



@endpush

