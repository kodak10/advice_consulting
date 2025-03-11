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

                @if($errors->any())
                    <div class="alert alert-danger text-danger" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
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
                                    <div class="mb-3 contact-occupation">
                                        <select class="form-control" name="pays_id" required>
                                            @foreach ($payss as $pays)
                                                <option value="{{ $pays->id }}" {{ old('pays_id') == $pays->id ? 'selected' : '' }}>
                                                    {{ $pays->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3 contact-name">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-user"></i></span>
                                            <input type="text" name="name" class="form-control" placeholder="Nom ou raison sociale" value="{{ old('name') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3 contact-occupation">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 contact-phone">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-phone"></i></span>
                                            <input type="text" name="phone" class="form-control" placeholder="Téléphone" value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="mb-3 contact-occupation">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="ti ti-address-book"></i></span>
                                            <input type="text" name="adresse" class="form-control" placeholder="Adresse" value="{{ old('adresse') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="mb-3 contact-occupation">
                                        <select class="form-control" name="role" required>
                                            <option value="Administrateur" {{ old('role') == 'Administrateur' ? 'selected' : '' }}>Administrateur</option>
                                            <option value="DG" {{ old('role') == 'DG' ? 'selected' : '' }}>Directeur Général</option>
                                            <option value="Daf" {{ old('role') == 'Daf' ? 'selected' : '' }}>DAF</option>
                                            <option value="Commercial" {{ old('role') == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                                            <option value="Comptable" {{ old('role') == 'Comptable' ? 'selected' : '' }}>Comptable</option>
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
                <tr>
                    <th>Pays</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Role</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($users as $user)
                <tr>
                    <td>{{ $user->pays->name }}</td>
                    <td>
                        <h6 class="mb-0">{{ $user->name }}</h6>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->roles->first()->name ?? 'Aucun rôle' }}</td>
                    <td>{{ $user->status ?? 'Non renseigné' }}</td>
                    <td>
                        <div class="action-btn text-center d-flex justify-content-between">
                            <a href="{{ route('dashboard.activate', $user->id) }}" class="text-primary" title="Activer">
                                <i class="ti ti-lock-open fs-5"></i>
                            </a>
                            <a href="{{ route('dashboard.disable', $user->id) }}" class="text-danger" title="Desactiver">
                                <i class="ti ti-lock fs-5"></i>
                            </a>
                            
                        </div>
                    </td>
                </tr>


                
                @empty
                    Aucun client enregistré.
                @endforelse
                
            </tbody>
            
              <tfoot>
                <tr>
                    <th>Pays</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
              </tfoot>
            </table>
          </div>
      </div>
    </div>
  

</div>
@endsection

