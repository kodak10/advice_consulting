@extends('administration.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Paramétrage du compte</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a class="text-muted text-decoration-none" href="{{ route('dashboard.') }}">Accueil</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Paramétrage du compte</li>
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
    <div class="row">
      @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
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
    </div>
    <div class="card">
     
      <div class="card-body">
        <div class="row">
          <div class="col-lg-6 d-flex align-items-stretch">
            <div class="card w-100 border position-relative overflow-hidden">
                <div class="card-body p-4">
                    <h4 class="card-title">Changé image de profil</h4>
                    <p class="card-subtitle mb-4">Changez votre photo de profil à partir d'ici</p>
                    <div class="text-center">
                        <img src="{{ asset(auth()->user()->image) }}" 
                             alt="modernize-img" 
                             class="img-fluid rounded-circle" 
                             width="120" 
                             height="120">
                        
                        <div class="row">
                          <div class="col-lg-6">
                            <form action="{{ route('dashboard.profil.image') }}" method="POST" enctype="multipart/form-data">
                              @csrf
                              @method('PUT')
          
                              <div class="d-flex align-items-center justify-content-center my-4 gap-6">
                                  <input type="file" name="image" class="form-control">
                              </div>
          
                              <button type="submit" class="btn btn-primary">Sauvegarder</button>
                          </form>
                          </div>
        
                        <div class="col-lg-6">
                          <form action="{{ route('dashboard.profil.resetImage') }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn bg-danger-subtle text-danger">Réinitialiser</button>
                        </form>
                        </div>
                        </div>
        
                        <p class="mb-0">JPG, GIF ou PNG autorisés. Taille maximale de 1 MB</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 d-flex align-items-stretch">
          <div class="card w-100 border position-relative overflow-hidden">
              <div class="card-body p-4">
                  <h4 class="card-title">Changer le mot de passe</h4>
                  <p class="card-subtitle mb-4">Pour changer votre mot de passe veuillez confirmer ici</p>
      
                  
                  <form method="POST" action="{{ route('dashboard.profil.updatePassword') }}">
                      @csrf 
      
                      <div class="mb-3">
                          <label for="current_password" class="form-label">Mot de passe actuel</label>
                          <input type="password" class="form-control" id="current_password" name="current_password" placeholder="****************" required>
                      </div>
                      <div class="mb-3">
                          <label for="new_password" class="form-label">Nouveau mot de passe</label>
                          <input type="password" class="form-control" id="new_password" name="new_password" placeholder="****************" required>
                      </div>
                      <div class="mb-3">
                          <label for="new_password_confirmation" class="form-label">Confirmez le nouveau mot de passe</label>
                          <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="****************" required>
                      </div>
                      <button type="submit" class="btn btn-primary">Mettre à jour le mot de passe</button>
                  </form>
              </div>
          </div>
      </div>
          <div class="col-12">
            <div class="card w-100 border position-relative overflow-hidden mb-0">
                <div class="card-body p-4">
                    <h4 class="card-title">Détails personnels</h4>
                    <p class="card-subtitle mb-4">Pour modifier vos informations personnelles, modifiez et enregistrez à partir d'ici</p>
                    
                    <form method="POST" action="{{ route('dashboard.profil.updateInformation') }}">
                        @csrf
                        @method('PUT')
        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Votre nom</label>
                                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" placeholder="Nom ou raison sociale">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" placeholder="Téléphone">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <input type="text" name="adresse" class="form-control" value="{{ $user->adresse }}" placeholder="Adresse">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                                    <button class="btn btn-primary" type="submit">Sauvegarder</button>
                                    <button class="btn bg-danger-subtle text-danger" type="button">Annuler</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
      </div>
    </div>
</div>
@endsection

@push('scripts')




@endpush

