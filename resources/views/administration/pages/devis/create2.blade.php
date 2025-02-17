@extends('administration.layouts.master')

@section('content')
<section id="devis-form" class="">
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
    <form action="{{ route('dashboard.devis.recap') }}" method="POST">
        @csrf
        @if(session('step') == 'recap')
            <!-- ÉTAPE RÉCAPITULATIVE -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Récapitulatif</h4>
                    <p><strong>Client :</strong> {{ session('data.client_nom') }}</p>
                    <p><strong>Date d'émission :</strong> {{ session('data.date_emission') }}</p>
                    <p><strong>Date d'échéance :</strong> {{ session('data.date_echeance') }}</p>
                    <p><strong>N° BC :</strong> {{ session('data.numero_bc') }}</p>

                    <h5>Désignations</h5>
                    <ul>
                        @foreach(session('data.designations') as $designation)
                            <li>{{ $designation['description'] }} - {{ $designation['quantite'] }} x {{ $designation['pu'] }} = {{ $designation['total'] }}</li>
                        @endforeach
                    </ul>

                    <h5>Total</h5>
                    <p><strong>Total HT :</strong> {{ session('data.total_ht') }}</p>
                    <p><strong>TVA :</strong> {{ session('data.tva') }}</p>
                    <p><strong>Total TTC :</strong> {{ session('data.total_ttc') }}</p>
                    <p><strong>Accompte :</strong> {{ session('data.accompte') }}</p>
                    <p><strong>Solde :</strong> {{ session('data.solde') }}</p>
                </div>
            </div>

            <div class="form-actions mb-5">
                <a href="{{ route('facture.create') }}" class="btn btn-secondary">Retour</a>
                <button type="submit" class="btn btn-success">Enregistrer</button>
            </div>
        @else

       
            <div class="row">
                <!-- Étape Informations Client -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Informations du Client</h4>
        
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-lg-9">
                                        <select class="select2 form-control" name="client_id">
                                            <option value="none">Sélectionner un client</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" 
                                                    @if(session('data.client_id') == $client->id) selected @endif>
                                                    {{ $client->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="button" class="btn bg-primary-subtle text-primary ">
                                            <span class="fs-4 me-1">+</span>
                                            Ajouter
                                        </button>
                                    </div>
                                </div>
                                
                            </div>
        
                        
                        </div>
                    </div>
                </div>

                
                <div class="col-lg-4">
                    <!-- Étape Dates -->
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Date</h4>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="form-label">Date d'Émission</label>
                                        <input type="date" name="date_emission" class="form-control mydatepicker" value="{{ session('data.date_emission', '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="form-label">Date d'Échéance</label>
                                        <input type="date" name="date_echeance" class="form-control mydatepicker" value="{{ session('data.date_echeance', '') }}">
                                    </div>
                                </div>
                            </div>
                            

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                        <h4 class="card-title mb-7">Désignations</h4>
            
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
    
                                <div class="col-md-3 mt-3 mt-md-0">
                                    <select class="select2 form-control">
                                    <option selected="">Selectionner</option>
    
                                    @foreach ($designations as $designation)
                                    <option value="{{ $designation->description }}">{{ $designation->description }}</option>
        
                                    @endforeach
                                    
                                    </select>                         
                                </div>
                                <div class="col-md-2 mt-3 mt-md-0">
                                    <input type="number" class="form-control" placeholder="Qte" value="1">
                                </div>
                                <div class="col-md-2 mt-3 mt-md-0">
                                    <input type="number" class="form-control" placeholder="PU">
                                </div>
                                <div class="col-md-2 mt-3 mt-md-0">
                                    <input type="number" class="form-control" placeholder="Remise">
                                </div>
                                <div class="col-md-2 mt-3 mt-md-0">
                                    <input type="number" class="form-control" placeholder="Total">
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
                                    Ajouter une autre
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Conditions financières</h4>
        
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="form-label">Commande</label>
                                        <input type="text" name="commande" class="form-control mydatepicker" value="{{ session('data.date_emission', '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="form-label">Livraison</label>
                                        <input type="text" name="livraison" class="form-control mydatepicker" value="{{ session('data.date_echeance', '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="form-label">Validité de l'offre</label>
                                        <input type="text" name="validite" class="form-control mydatepicker" value="{{ session('data.date_echeance', '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="form-label">Delai de livraison</label>
                                        <input type="text" name="delai" class="form-control mydatepicker" value="{{ session('data.date_echeance', '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Banque</h4>
        
                            <select class="select2 form-control" name="banque_id">
                                <option value="none">Sélectionner une banque</option>
                                @foreach ($banques as $banque)
                                    <option value="{{ $banque->id }}" 
                                        @if(session('data.banque_id') == $banque->id) selected @endif>
                                        {{ $banque->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                

                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-7">
                                <h4 class="card-title">Les conditions</h4>
                
                                <button class="navbar-toggler border-0 shadow-none d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                <i class="ti ti-menu fs-5 d-flex"></i>
                                </button>
                            </div>
                            <div class="row">
                                
                                <div class="col-4">
                                    <label class="form-label">Total HT <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="0">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">TVA 18% <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="0.18">
                                </div>
                                <div class="col-4">
                                    <div class="mb-4">
                                        <label class="form-label">Total TTC <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="0">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Accompte <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="0">
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Solde <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="0">
                                </div>
                            </div>
                        </div>
                  </div>
                </div>
            </div>
            
            <div class="form-actions mb-5">
                <button type="submit" class="btn btn-success">Recapitulaif</button>
            </div>
        @endif

    </form>

</section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Ajouter une nouvelle désignation
    document.getElementById('add-designation').addEventListener('click', function() {
        const container = document.getElementById('designations-container');
        const index = container.children.length;
        
        // Créer un nouvel élément de désignation
        const newDesignation = document.createElement('div');
        newDesignation.classList.add('mb-4');
        newDesignation.classList.add('designation-item');
        newDesignation.innerHTML = `
            <label class="form-label">Désignation ${index + 1}</label>
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="designations[${index}][description]" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="designations[${index}][quantite]" class="form-control" min="1" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="designations[${index}][pu]" class="form-control" min="0" required>
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" name="designations[${index}][total]" class="form-control" readonly>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-designation">Supprimer</button>
                </div>
            </div>
        `;
        
        // Ajouter la nouvelle désignation au formulaire
        container.appendChild(newDesignation);
        
        // Calculer automatiquement le total de la désignation
        newDesignation.querySelector('input[name$="[quantite]"]').addEventListener('input', updateTotal);
        newDesignation.querySelector('input[name$="[pu]"]').addEventListener('input', updateTotal);
    });

    // Supprimer une désignation
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-designation')) {
            event.target.closest('.designation-item').remove();
        }
    });

    // Calculer le total pour chaque désignation
    function updateTotal(event) {
        const row = event.target.closest('.row');
        const quantite = parseFloat(row.querySelector('input[name$="[quantite]"]').value) || 0;
        const pu = parseFloat(row.querySelector('input[name$="[pu]"]').value) || 0;
        const total = quantite * pu;
        row.querySelector('input[name$="[total]"]').value = total.toFixed(2);
    }
});

    </script>
@endpush
