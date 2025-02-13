@extends('administration.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="card bg-info-subtle shadow-none position-relative overflow-hidden mb-4">
      <div class="card-body px-4 py-3">
        <div class="row align-items-center">
          <div class="col-9">
            <h4 class="fw-semibold mb-8">Ajouter un dévis</h4>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a class="text-muted text-decoration-none" href="/dashboard">Accueil</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Ajouter un dévis</li>
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
    
    

    {{-- <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-7">Informations du client</h4>
                  <div class="mb-3">
                    <label class="form-label">Clients</label>
                    <div class="row">
                        <div class="col-lg-8">
                            <select class="select2 form-control" id="client-select">
                                <option value="none">Slectionner un client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" 
                                        data-nom="{{ $client->nom }}" 
                                        data-adresse="{{ $client->adresse }}" 
                                        data-telephone="{{ $client->telephone }}" 
                                        data-cc="{{ $client->cc }}" 
                                        data-nttn="{{ $client->nttn }}">
                                        {{ $client->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                
                        <div class="col-lg-4">
                            <button type="button" class="btn bg-primary-subtle text-primary ">
                                <span class="fs-4 me-1">+</span>
                                Ajouter
                            </button>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="mb-4 d-flex">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" id="client-nom" class="form-control" value="N/A">
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-4 d-flex">
                                <label class="form-label">Adresse <span class="text-danger">*</span></label>
                                <input type="text" id="client-adresse" class="form-control" value="N/A">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-4 d-flex">
                                <label class="form-label">Telephone <span class="text-danger">*</span></label>
                                <input type="text" id="client-telephone" class="form-control" value="N/A">
                            </div>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-4 d-flex">
                                <label class="form-label">N°CC <span class="text-danger">*</span></label>
                                <input type="text" id="client-cc" class="form-control" value="N/A">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-4 d-flex">
                                <label class="form-label">NTTN <span class="text-danger">*</span></label>
                                <input type="text" id="client-nttn" class="form-control" value="N/A">
                            </div>
                        </div>
                    </div>
                </div>
              
                 
                </div>
              </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                  
                  <form action="" class="form-horizontal">
                    <div class="mb-4">
                      <label class="form-label">Date d'emission <span class="text-danger">*</span>
                      </label>
                      <div class="input-group">
                        <input type="text" class="form-control mydatepicker" placeholder="mm/dd/yyyy">
  
                        <span class="input-group-text">
                          <i class="ti ti-calendar fs-5"></i>
                        </span>
                      </div>
                    </div>

                    <div class="mb-4">
                      <label class="form-label">Date d'échéance <span class="text-danger">*</span>
                      </label>
                      <div class="input-group">
                        <input type="text" class="form-control mydatepicker" placeholder="mm/dd/yyyy">
  
                        <span class="input-group-text">
                          <i class="ti ti-calendar fs-5"></i>
                        </span>
                      </div>
                    </div>


                    <div class="mb-4">
                      <label class="form-label">N°BC <span class="text-danger">*</span>
                      </label>
                      <div class="input-group">
                        <input type="text" class="form-control mydatepicker" placeholder="mm/dd/yyyy">
                      </div>
                    </div>

                    <div class="mb-4">
                      <label class="form-label">N° BAP <span class="text-danger">*</span>
                      </label>
                      <div class="input-group">
                        <input type="text" class="form-control mydatepicker" placeholder="mm/dd/yyyy">
                      </div>
                    </div>

                    <div class="mb-4">
                      <label class="form-label">N° BL <span class="text-danger">*</span>
                      </label>
                      <div class="input-group">
                        <input type="text" class="form-control mydatepicker" placeholder="mm/dd/yyyy">
                      </div>
                    </div>
                    
                  </form>
                </div>
              </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                  <h4 class="card-title mb-7">Désignations</h4>
      
                  <form action="">
                    <div class="email-repeater mb-3">
                      <div data-repeater-list="repeater-group">
                        <div data-repeater-item="" class="row mb-3">

                          <div class="col-md-6 mt-3 mt-md-0 d-none">
                            <select class="select2 form-control">
                              <option selected="">Ref</option>
                              <option>Material</option>
                              <option>Style</option>
                            </select>                          
                          </div>

                          <div class="col-md-5 mt-3 mt-md-0">
                            <select class="select2 form-control">
                              <option selected="">Selectionner</option>

                              @foreach ($designations as $designation)
                              <option value="{{ $designation->description }}">{{ $designation->description }}</option>
 
                              @endforeach
                              
                            </select>                         
                          </div>
                          <div class="col-md-2 mt-3 mt-md-0">
                            <input type="text" class="form-control" placeholder="Qte">
                          </div>
                          <div class="col-md-2 mt-3 mt-md-0">
                            <input type="text" class="form-control" placeholder="PU">
                          </div>
                          <div class="col-md-2 mt-3 mt-md-0">
                            <input type="text" class="form-control" placeholder="Total">
                          </div>
                          
                          <div class="col-md-1 mt-3 mt-md-0">
                            <button data-repeater-delete="" class="btn bg-danger-subtle text-danger" type="button">
                              <i class="ti ti-x fs-5 d-flex"></i>
                            </button>
                          </div>
                        </div>
                      </div>
                      <button type="button" data-repeater-create="" class="btn bg-primary-subtle text-primary ">
                        <span class="fs-4 me-1">+</span>
                        Add another variation
                      </button>
                    </div>
                  </form>
                </div>
              </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between mb-7">
                    <h4 class="card-title">Status</h4>
                    <div class="p-2 h-100 bg-success rounded-circle"></div>
                  </div>
                  <form action="" class="form-horizontal">
                    <div>
                      <select class="form-select mr-sm-2  mb-2" id="inlineFormCustomSelect">
                        <option selected="">Published</option>
                        <option value="1">Draft</option>
                        <option value="2">Sheduled</option>
                        <option value="3">Inactive</option>
                      </select>
                      <p class="fs-2 mb-0">
                        Set the product status.
                      </p>
                    </div>
                  </form>
                </div>
              </div>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-7">
                    <h4 class="card-title">Les conditions</h4>
      
                    <button class="navbar-toggler border-0 shadow-none d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                      <i class="ti ti-menu fs-5 d-flex"></i>
                    </button>
                  </div>
                  <form action="" class="form-horizontal">
                    <!-- Remise spéciale sur une ligne entière -->
                    <div class="mb-4">
                        <label class="form-label">Remise spéciale <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="N/A">
                    </div>
                
                    <!-- Total HT et TVA sur la même ligne -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Total HT <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="N/A">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">TVA 18% <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="N/A">
                        </div>
                    </div>
                
                    <!-- Total TTC sur une ligne entière -->
                    <div class="mb-4">
                        <label class="form-label">Total TTC <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" value="N/A">
                    </div>
                
                    <!-- Accompte et Solde sur la même ligne -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Accompte <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="N/A">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Solde <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="N/A">
                        </div>
                    </div>
                </form>
                
                </div>
              </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Banque</h4>
      
                    <button class="navbar-toggler border-0 shadow-none d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                      <i class="ti ti-menu fs-5 d-flex"></i>
                    </button>
                  </div>
                  <form action="" class="form-horizontal">
                    <div class="mb-4">
                      <label class="form-label">Product Name <span class="text-danger">*</span>
                      </label>
                      <input type="text" class="form-control" value="Product Name">
                      <p class="fs-2">A product name is required and recommended to be unique.</p>
                    </div>
                    
                  </form>
                </div>
            </div>

            <div class="offcanvas-md offcanvas-end overflow-auto" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
              <div class="card">
                <div class="card-body">
                  <form action="#" class="dropzone dz-clickable mb-2">
                    <div class="dz-default dz-message">
                      <button class="dz-button" type="button">Importer les images</button>
                    </div>
                  </form>
                  <p class="fs-2 text-center mb-0">
                    Joindre des images. Seule les images au format *.png, *.jpg and *.jpeg sont acceptées.
                  </p>
                </div>
              </div>
             
             
              
        </div>
        </div>
    </div>

    <div class="row">
       

        <div class="col-lg-4">
            
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="form-actions mb-5">
                <button type="submit" class="btn btn-primary">
                  Save changes
                </button>
                <button type="button" class="btn bg-danger-subtle text-danger ms-6">
                  Cancel
                </button>
            </div>
        </div>
    </div> --}}

    <form action="{{ route('dashboard.devis.store') }}" method="POST">
      
      @csrf

      <div class="row">
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
        <div class="row">
          <div class="col-lg-8">
              <div class="card">
                  <div class="card-body">
                      <h4 class="card-title mb-7">Informations du client</h4>
                      <div class="mb-3">
                          <label class="form-label">Clients</label>
                          <div class="row">
                              <div class="col-lg-8">
                                  <select class="select2 form-control" id="client-select">
                                      <option value="none">Sélectionner un client</option>
                                      @foreach ($clients as $client)
                                          <option value="{{ $client->id }}" 
                                              data-nom="{{ $client->nom }}" 
                                              data-adresse="{{ $client->adresse }}" 
                                              data-telephone="{{ $client->telephone }}" 
                                              data-cc="{{ $client->cc }}" 
                                              data-nttn="{{ $client->nttn }}">
                                              {{ $client->nom }}
                                          </option>
                                      @endforeach
                                  </select>
                              </div>
                              <div class="col-lg-4">
                                  <button type="button" class="btn bg-primary-subtle text-primary">
                                      <span class="fs-4 me-1">+</span> Ajouter
                                  </button>
                              </div>
                          </div>
                      </div>
      
                      <div class="row">
                          <div class="mb-4 d-flex">
                              <label class="form-label">Nom <span class="text-danger">*</span></label>
                              <input type="text" id="client-nom" class="form-control" value="N/A">
                          </div>
                      </div>
      
                      <!-- Autres champs similaires ici -->
      
                  </div>
              </div>
          </div>
      
          <div class="col-lg-4">
              <div class="card">
                  <div class="card-body">
                      <!-- Formulaire de dates -->
                     
                          <!-- Champs Date -->
                          <div class="mb-4">
                              <label class="form-label">Date d'émission <span class="text-danger">*</span></label>
                              <div class="input-group">
                                  <input type="text" class="form-control mydatepicker" name="date_emission" id="date-emission" placeholder="mm/dd/yyyy">
                                  <span class="input-group-text"><i class="ti ti-calendar fs-5"></i></span>
                              </div>
                          </div>
      
                          <div class="mb-4">
                              <label class="form-label">Date d'échéance <span class="text-danger">*</span></label>
                              <div class="input-group">
                                  <input type="text" class="form-control mydatepicker" name="date_echeance" id="date-echeance" placeholder="mm/dd/yyyy">
                                  <span class="input-group-text"><i class="ti ti-calendar fs-5"></i></span>
                              </div>
                          </div>
      
                          <!-- Champs Numéro -->
                          <div class="mb-4">
                              <label class="form-label">N°BC <span class="text-danger">*</span></label>
                              <div class="input-group">
                                  <input type="text" class="form-control" id="numero-bc" placeholder="Numéro BC">
                              </div>
                          </div>
      
                          <!-- Autres champs similaires ici -->
                      
                  </div>
              </div>
          </div>
      </div>
      
      <div class="row">
          <div class="col-lg-8">
              <div class="card">
                  <div class="card-body">
                      <h4 class="card-title mb-7">Désignations</h4>
                     
                          <div class="email-repeater mb-3">
                              <div data-repeater-list="repeater-group">
                                  <div data-repeater-item="" class="row mb-3">
                                      <!-- Champs Désignation, Quantité, PU, Total -->
                                      <div class="col-md-5 mt-3">
                                          <select class="select2 form-control designation-select" name="designations[]">
                                              <option selected="">Sélectionner</option>
                                              @foreach ($designations as $designation)
                                                  <option value="{{ $designation->description }}">{{ $designation->description }}</option>
                                              @endforeach
                                          </select>
                                      </div>
                                      <div class="col-md-2 mt-3">
                                          <input type="number" class="form-control quantity" placeholder="Qte">
                                      </div>
                                      <div class="col-md-2 mt-3">
                                          <input type="number" class="form-control pu" placeholder="PU">
                                      </div>
                                      <div class="col-md-2 mt-3">
                                          <input type="number" class="form-control total" placeholder="Total" disabled>
                                      </div>
                                      <div class="col-md-1 mt-3">
                                          <button data-repeater-delete="" class="btn bg-danger-subtle text-danger" type="button">
                                              <i class="ti ti-x fs-5 d-flex"></i>
                                          </button>
                                      </div>
                                  </div>
                              </div>
                              <button type="button" data-repeater-create="" class="btn bg-primary-subtle text-primary">
                                  <span class="fs-4 me-1">+</span> Ajouter une variation
                              </button>
                          </div>
                      
                  </div>
              </div>
          </div>
      
          <div class="col-lg-4">
              <div class="card">
                  <div class="card-body">
                      <!-- Résumé des totaux -->
                      <h4 class="card-title">Les conditions</h4>
                     
                          <div class="mb-4">
                              <label class="form-label">Remise spéciale <span class="text-danger">*</span></label>
                              <input type="text" class="form-control remise" value="0">
                          </div>
      
                          <!-- Total HT et TVA sur la même ligne -->
                          <div class="row mb-4">
                              <div class="col-md-6">
                                  <label class="form-label">Total HT <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control total-ht" value="0">
                              </div>
                              <div class="col-md-6">
                                  <label class="form-label">TVA 18% <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control tva" value="0.18">
                              </div>
                          </div>
      
                          <div class="mb-4">
                              <label class="form-label">Total TTC <span class="text-danger">*</span></label>
                              <input type="text" class="form-control total-ttc" value="0">
                          </div>
      
                          <div class="row mb-4">
                              <div class="col-md-6">
                                  <label class="form-label">Accompte <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control accompte" value="0">
                              </div>
                              <div class="col-md-6">
                                  <label class="form-label">Solde <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control solde" value="0">
                              </div>
                          </div>
                      
                  </div>
              </div>
          </div>
      </div>
      <button type="submit" class="btn btn-success">Soumettre</button>

    </form>
  </div>
@endsection

@push('scripts')
<script>
  $(document).ready(function(){
      $('#client-select').change(function() {
          // Récupérer l'option sélectionnée
          var selectedOption = $(this).find('option:selected');
          
          // Extraire les données de l'option
          var nom = selectedOption.data('nom');
          var adresse = selectedOption.data('adresse');
          var telephone = selectedOption.data('telephone');
          var cc = selectedOption.data('cc');
          var nttn = selectedOption.data('nttn');
          
          // Mettre à jour les champs de texte
          $('#client-nom').val(nom);
          $('#client-adresse').val(adresse);
          $('#client-telephone').val(telephone);
          $('#client-cc').val(cc);
          $('#client-nttn').val(nttn);
      });
  });
</script>

<script>
$(document).ready(function() {
    // Fonction pour calculer le total pour chaque ligne
    $(document).on('input', '.quantity, .pu', function() {
        let quantity = $(this).closest('.row').find('.quantity').val();
        let pu = $(this).closest('.row').find('.pu').val();
        let total = quantity * pu;
        $(this).closest('.row').find('.total').val(total);

        // Mettre à jour les totaux généraux (HT, TVA, TTC)
        updateTotals();
    });

    // Fonction pour mettre à jour les totaux
    function updateTotals() {
        let totalHT = 0;
        $('.total').each(function() {
            totalHT += parseFloat($(this).val()) || 0;
        });

        let remise = parseFloat($('.remise').val()) || 0;
        
        // Appliquer la remise sur le total HT
        let totalHTAvecRemise = totalHT - remise;

        // Calcul de la TVA (18%)
        let tva = totalHTAvecRemise * 0.18;
        
        // Calcul du total TTC
        let totalTTC = totalHTAvecRemise + tva;
        
        // Calcul du solde
        let accompte = parseFloat($('.accompte').val()) || 0;
        let solde = totalTTC - accompte;

        // Mettre à jour les champs avec les nouveaux totaux
        $('.total-ht').val(totalHTAvecRemise.toFixed(2));
        $('.tva').val('18%');  // Afficher '18%' dans le champ TVA
        $('.total-ttc').val(totalTTC.toFixed(2));
        $('.solde').val(solde.toFixed(2));

        // Si une remise est appliquée, l'afficher dans le champ de remise
        $('.remise-affiche').text(remise.toFixed(2));
    }
});




</script>
@endpush