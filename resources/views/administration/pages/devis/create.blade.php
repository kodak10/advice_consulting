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

       
            <!-- Étape Informations Client -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Informations du Client</h4>

                    <div class="mb-3">
                        <label class="form-label">Clients</label>
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

                   
                </div>
            </div>

            
            <!-- Étape Dates -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Dates</h4>

                    <div class="mb-4">
                        <label class="form-label">Date d'Émission</label>
                        <input type="date" name="date_emission" class="form-control mydatepicker" value="{{ session('data.date_emission', '') }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Date d'Échéance</label>
                        <input type="date" name="date_echeance" class="form-control mydatepicker" value="{{ session('data.date_echeance', '') }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">N° BC</label>
                        <input type="text" name="numero_bc" class="form-control" value="{{ session('data.numero_bc', '') }}">
                    </div>
                </div>
            </div>

            <!-- Étape Désignations -->
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

                          <div class="col-md-5 mt-3 mt-md-0">
                            <select class="select2 form-control">
                              <option selected="">Selectionner</option>

                              @foreach ($designations as $designation)
                              <option value="{{ $designation->description }}">{{ $designation->description }}</option>
 
                              @endforeach
                              
                            </select>                         
                          </div>
                          <div class="col-md-2 mt-3 mt-md-0">
                            <input type="number" class="form-control" placeholder="Qte">
                          </div>
                          <div class="col-md-2 mt-3 mt-md-0">
                            <input type="number" class="form-control" placeholder="PU">
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
                        Add another variation
                      </button>
                    </div>
                </div>
              </div>

           
            <!-- Étape Conditions -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Désignations</h4>
            
                    <!-- Boucle pour afficher plusieurs désignations -->
                    <div id="designations-container">
                        @foreach(session('data.designations', []) as $index => $designation)
                            <div class="mb-4" class="designation-item">
                                <label class="form-label">Désignation {{ $index + 1 }}</label>
            
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" name="designations[{{ $index }}][description]" class="form-control" value="{{ $designation['description'] }}" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="designations[{{ $index }}][quantite]" class="form-control" value="{{ $designation['quantite'] }}" min="1" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" step="0.01" name="designations[{{ $index }}][pu]" class="form-control" value="{{ $designation['pu'] }}" min="0" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" step="0.01" name="designations[{{ $index }}][total]" class="form-control" value="{{ $designation['total'] }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-designation">Supprimer</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
            
                    <div class="mb-4">
                        <button type="button" class="btn btn-primary" id="add-designation">Ajouter une désignation</button>
                    </div>
                </div>
            </div>
            

            
            <!-- Étape Banque -->
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Banque</h4>

                    <div class="mb-4">
                        <label class="form-label">Nom du produit</label>
                        <input type="text" name="product_name" class="form-control" value="{{ session('data.product_name', '') }}">
                    </div>

                    <p class="fs-2">Un nom de produit est requis et doit être unique.</p>
                </div>
            </div>

            <div class="form-actions mb-5">
                <button type="submit" class="btn btn-success">Finaliser</button>
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
