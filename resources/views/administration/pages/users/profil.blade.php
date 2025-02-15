@extends('administration.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Account Setting</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a class="text-muted text-decoration-none" href="index.html">Home</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Account Setting</li>
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
    <div class="card">
     
      <div class="card-body">
        <div class="row">
          <div class="col-lg-6 d-flex align-items-stretch">
            <div class="card w-100 border position-relative overflow-hidden">
              <div class="card-body p-4">
                <h4 class="card-title">Changé image de profil</h4>
                <p class="card-subtitle mb-4">Changez votre photo de profil à partir d'ici</p>
                <div class="text-center">
                  <img src="{{ asset('adminAssets/images/profile/user-1.jpg') }}" alt="modernize-img" class="img-fluid rounded-circle" width="120" height="120">
                  <div class="d-flex align-items-center justify-content-center my-4 gap-6">
                    <button class="btn btn-primary">Télécharger</button>
                    <button class="btn bg-danger-subtle text-danger">Réinitialiser</button>
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
                <form>
                  <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Mot de passe actuel</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" value="12345678910">
                  </div>
                  <div class="mb-3">
                    <label for="exampleInputPassword2" class="form-label">Nouveau mot de passe</label>
                    <input type="password" class="form-control" id="exampleInputPassword2" value="12345678910">
                  </div>
                  <div>
                    <label for="exampleInputPassword3" class="form-label">Confirmez le mot de passe</label>
                    <input type="password" class="form-control" id="exampleInputPassword3" value="12345678910">
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="card w-100 border position-relative overflow-hidden mb-0">
              <div class="card-body p-4">
                <h4 class="card-title">Détails personnels</h4>
                <p class="card-subtitle mb-4">Pour modifier vos informations personnelles, modifiez et enregistrez à partir d'ici</p>
                <form>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="mb-3">
                        <label for="exampleInputtext" class="form-label">Votre nom</label>
                        <input type="text" name="name" class="form-control" id="exampleInputtext" placeholder="Nom ou raison sociale">
                      </div>
                      
                      
                    </div>
                    <div class="col-lg-6">
                      <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="exampleInputtext" placeholder="email@email.com" disabled>

                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="mb-3">
                        <label for="exampleInputtext2" class="form-label">Téléphone</label>
                        <input type="text" name="phone" class="form-control" id="exampleInputtext2" placeholder="téléphone">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="mb-3">
                        <label for="exampleInputtext2" class="form-label">Adresse</label>
                        <input type="text" name="adresse" class="form-control" id="exampleInputtext2" placeholder="Adresse">
                      </div>
                      
                      
                    </div>
                   
                    
                    <div class="col-12">
                      <div class="d-flex align-items-center justify-content-end mt-4 gap-6">
                        <button class="btn btn-primary">Sauvegarder</button>
                        <button class="btn bg-danger-subtle text-danger">Annuler</button>
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

