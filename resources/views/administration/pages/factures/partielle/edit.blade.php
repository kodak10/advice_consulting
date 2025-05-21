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

    <form action="{{ route('dashboard.devis.recapUpdate', $devis->id) }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Informations du Client</h4>
                        <div class="mb-3 row">
                            <div class="col-lg-9">
                                <select class="select2 form-control" name="client_id">
                                    <option value="none">Sélectionner un client</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" {{ $devis->client_id == $client->id ? 'selected' : '' }}>
                                            {{ $client->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Dates</h4>
                        <div class="row">
                            <div class="col-lg-6">
                                <label class="form-label">Date d'Émission</label>
                                <input type="date" name="date_emission" value="{{ $devis->date_emission }}" class="form-control">
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Date d'Échéance</label>
                                <input type="date" name="date_echeance" value="{{ $devis->date_echeance }}" class="form-control">
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
                        <h4 class="card-title">Désignations</h4>
                        <div class="email-repeater mb-3">
                            <div data-repeater-list="designations">
                                <div data-repeater-item class="row mb-3">
                                    <div class="col-md-3 mt-3 mt-md-0">
                                        <select class="select2 form-control designation" name="designations[][designation]">
                                            <option value="">Sélectionner</option>
                                            @foreach ($designations as $designation)
                                                <option value="{{ $designation->id }}" data-price="{{ $designation->prix_unitaire }}">
                                                    {{ $designation->description }}
                                                </option>
                                                <input type="hidden" class="form-control" name="designations[][id]" value="{{ $designation->id }}">

                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="col-md-2 mt-3 mt-md-0">
                                        <input type="number" class="form-control quantity" name="designations[][quantity]" placeholder="Qte" value="0" min="1">
                                    </div>
                                    <div class="col-md-2 mt-3 mt-md-0">
                                        <input type="number" class="form-control price" name="designations[][price]" placeholder="PU">
                                    </div>
                                    <div class="col-md-2 mt-3 mt-md-0">
                                        <input type="number" class="form-control discount" name="designations[][discount]" placeholder="Remise" value="0" min="0">
                                    </div>
                                    <div class="col-md-2 mt-3 mt-md-0">
                                        <input type="number" class="form-control total" name="designations[][total]" placeholder="Total" readonly>
                                    </div>
                                    <div class="col-md-1 mt-3 mt-md-0">
                                        <button data-repeater-delete class="btn bg-danger-subtle text-danger" type="button">
                                            <i class="ti ti-x fs-5 d-flex"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" data-repeater-create class="btn bg-primary-subtle text-primary">
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
                                <div>
                                    <label class="form-label">Commande</label>
                                    <input type="text" name="commande" class="form-control mydatepicker @error('commande') is-invalid @enderror" 
                                        value="{{ $devis->commande }}">
                                    @error('commande')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label">Livraison</label>
                                    <input type="text" name="livraison" class="form-control mydatepicker @error('livraison') is-invalid @enderror" 
                                        value="{{ $devis->livraison }}">
                                    @error('livraison')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label">Validité de l'offre</label>
                                    <input type="text" name="validite" class="form-control mydatepicker @error('validite') is-invalid @enderror" 
                                        value="{{ $devis->validite }}">
                                    @error('validite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div>
                                    <label class="form-label">Délai de livraison</label>
                                    <input type="text" name="delai" class="form-control mydatepicker @error('delai') is-invalid @enderror" 
                                        value="{{ $devis->delai }}">
                                    @error('delai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                        
                        <select class="select2 form-control @error('banque_id') is-invalid @enderror" name="banque_id">
                            <option value="none">Sélectionner une banque</option>
                            @foreach ($banques as $banque)
                                <option value="{{ $banque->id }}" {{ $banque->banque_id == $banque->id ? 'selected' : '' }}>
                                    {{ $banque->name }}
                                </option>
                            @endforeach

                            

                        </select>
                        @error('banque_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-7">
                            <h4 class="card-title">Les conditions</h4>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label">Total HT <span class="text-danger">*</span></label>
                                <input type="text" name="total_ht" class="form-control" 
                                    value="{{ $devis->total_ht }}" readonly>
                            </div>
                            <div class="col-4">
                                <label class="form-label">TVA 18% <span class="text-danger">*</span></label>
                                <input type="text" name="tva" class="form-control @error('tva') is-invalid @enderror" 
                                    value="{{ $devis->tva }}">
                                @error('tva')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-4">
                                <div class="mb-4">
                                    <label class="form-label">Total TTC <span class="text-danger">*</span></label>
                                    <input type="text" name="total_ttc" class="form-control" 
                                        value="{{ $devis->total_ttc }}" readonly>
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Acompte <span class="text-danger">*</span></label>
                                <input type="text" name="acompte" class="form-control @error('acompte') is-invalid @enderror" 
                                    value="{{ $devis->acompte }}">
                                @error('acompte')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-4">
                                <label class="form-label">Solde <span class="text-danger">*</span></label>
                                <input type="text" name="solde" class="form-control" 
                                    value="{{ $devis->solde }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="form-actions mb-5">
            <button type="submit" class="btn btn-success">Enregistrer</button>
        </div>
    </form>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        function updateTotal(row) {
            var price = parseFloat(row.find('.price').val()) || 0;
            var quantity = parseInt(row.find('.quantity').val()) || 1;
            var discount = parseFloat(row.find('.discount').val()) || 0;

            var total = (price * quantity) - discount;
            if (total < 0) total = 0; // Empêcher un total négatif

            row.find('.total').val(total.toFixed(2)); // Afficher avec 2 décimales
        }

        // Mise à jour du prix unitaire lorsqu'on sélectionne une désignation
        $(document).on('change', '.designation', function() {
            var selectedOption = $(this).find(':selected');
            var price = parseFloat(selectedOption.data('price')) || 0;

            var row = $(this).closest('.row');
            row.find('.price').val(price); // Mettre à jour le prix unitaire
            updateTotal(row);
        });

        // Mise à jour du total lorsque la quantité ou la remise change
        $(document).on('input', '.quantity, .discount', function() {
            var row = $(this).closest('.row');
            updateTotal(row);
        });

        // Fonction pour mettre à jour le total HT
    function updateTotalHT() {
        var totalHT = 0;
        $('.email-repeater .row').each(function () {
            var row = $(this);
            var total = parseFloat(row.find('.total').val()) || 0;
            totalHT += total;
        });

        // Mise à jour de Total HT
        $('.col-4 input[value="0"]').eq(0).val(totalHT.toFixed(2));

        updateTVAandTTC(totalHT);
    }

    // Fonction pour mettre à jour TVA et Total TTC
    function updateTVAandTTC(totalHT) {
        var tva = 0.18; // TVA 18%
        var ttc = totalHT + (totalHT * tva); // Calcul correct du Total TTC

        // Mise à jour de TVA (fixe à 18%)
        $('.col-4 input[value="0"]').eq(1).val((tva * 100).toFixed(2));  // TVA en pourcentage

        // Mise à jour de Total TTC
        $('.col-4 input[value="0"]').eq(2).val(ttc.toFixed(2));

        updateSolde(ttc);
    }

    // Fonction pour mettre à jour le solde
    function updateSolde(ttc) {
        var acompte = parseFloat($('.col-4 input[value="0"]').eq(3).val()) || 0;
        var solde = ttc - acompte;

        // Mise à jour du solde
        $('.col-4 input[value="0"]').eq(4).val(solde.toFixed(2));
    }

    // Quand l'acompte change, mettre à jour le solde
    $(document).on('input', '.col-4 input[value="0"]:eq(3)', function () {
        var totalTTC = parseFloat($('.col-4 input[value="0"]').eq(2).val()) || 0;
        updateSolde(totalTTC);
    });

    // Chaque fois qu'un champ "total" change (ajout/remise)
    $(document).on('input', '.quantity, .discount, .total', function () {
        updateTotalHT();
    });


    // Chaque fois qu'une ligne est ajoutée
    $(document).on('click', '[data-repeater-create]', function () {
        updateTotalHT();
    });
    
    // Chaque fois qu'une ligne est supprimée
    $(document).on('click', '[data-repeater-delete]', function () {
        updateTotalHT();
    });


        // Gérer l'ajout dynamique d'une nouvelle ligne
        // Gérer l'ajout dynamique d'une nouvelle ligne
        $(document).on('click', '[data-repeater-create]', function() {
            var lastRow = $('.email-repeater [data-repeater-item]:last');
            var newRow = lastRow.clone();

            // Réinitialiser les valeurs des champs
            newRow.find('input').val('');
            newRow.find('.quantity').val(1);
            newRow.find('.discount').val(0);
            newRow.find('.total').val(0);
            newRow.find('.price').val('');

            // Réappliquer Select2 si utilisé
            newRow.find('.designation').val('').trigger('change');
            newRow.find('.designation').select2(); // Initialiser Select2 sur le nouvel élément

            // Ajouter la nouvelle ligne au DOM
            $('.email-repeater [data-repeater-list]').append(newRow);
        });

        // Initialiser Select2 pour les éléments existants
        $('.designation').select2();
    });
</script>
@endpush