@extends('administration.layouts.master')

@section('content')
<div class="container-fluid">
           
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Compte Utilisateurs</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a class="text-muted text-decoration-none" href="{{ route('dashboard.') }}">Accueil</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Compte Utilisateurs</li>
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
                Ajouter un Utilisateur
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
                <h5 class="modal-title">Information de l'utilisateur</h5>
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
                        <form class="{{ route('dashboard.storeUser') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 contact-name">
                                        <input type="text" name="name" class="form-control" placeholder="Nom ou raison sociale" required>
                                        <span class="validation-text text-danger"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 contact-occupation">
                                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 contact-phone">
                                    <input type="text" name="phone" class="form-control" placeholder="Téléphone">
                                    <span class="validation-text text-danger"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3 contact-occupation">
                                        <input type="text" name="adresse" class="form-control" placeholder="Adresse">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3 contact-occupation">
                                        <select class="form-control" name="role" required>
                                            <option value="Administrateur">Administrateur</option>
                                            <option value="Commercial">Commercial</option>
                                            <option value="Comptable">Comptable</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <div class="d-flex gap-6 m-0">
                                    <button type="submit" class="btn btn-success">Ajouter</button>
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


      <div class="card card-body">
        <div class="table-responsive">
            <table id="zero_config" class="table table-striped table-bordered text-nowrap align-middle">
              <thead>
                <!-- start row -->
                <tr>
                  <th>Nom</th>
                  <th>Email</th>
                  <th>Téléphone</th>
                  <th>Role</th>
                  <th>Statut</th>
                  <th>Action</th>
                </tr>
                <!-- end row -->
              </thead>
              <tbody>
                @forelse ($users as $user)
                <tr>
                    <td>
                        <h6 class="mb-0">{{ $user->name }}</h6>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->roles->first()->name ?? 'Aucun rôle' }}</td>
                    <td>{{ $user->status ?? 'Non renseigné' }}</td>
                    <td>
                        <div class="action-btn text-center">
                            <a href="{{ route('dashboard.disable', $user->id) }}" class="text-primary" title="Desactiver">
                                <i class="ti ti-pencil fs-5"></i>
                            </a>
                            
                            
                        </div>
                    </td>
                </tr>


                
                @empty
                    Aucun client enregistré.
                @endforelse
                
            </tbody>
            
              <tfoot>
                <!-- start row -->
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Role</th>
                    <th>Status</th>
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
{{-- <script>
  document.getElementById('btn-add').addEventListener('click', function(event) {
    event.preventDefault();

    // Récupérer les données du formulaire
    let formData = {
        nom: document.getElementById('c-name').value,
        numero_cc: document.getElementById('c-occupation').value,
        telephone: document.getElementById('c-phone').value,
        adresse: document.getElementById('c-adresse').value,
        ville: document.getElementById('c-ville').value,
        attn: document.getElementById('c-attn').value,
    };

    // Envoi de la requête avec fetch
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
            // Afficher le toast de succès
            toastr.success(data.message, 'Succès', {
                positionClass: 'toast-top-right',
                timeOut: 5000,
                closeButton: true,
                progressBar: true,
            });

            // Fermer le modal et recharger la page après un délai
            $('#addContactModal').modal('hide');
            setTimeout(() => {
                location.reload();  // Recharger la page pour afficher les nouveaux clients
            }, 5000);
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
}); --}}

</script>

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

