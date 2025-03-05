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

        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Devise</h4>
                <select name="devise" class="form-control">
                    <option value="XOF" {{ old('devise', $devis->devise ?? '') == 'XOF' ? 'selected' : '' }}>Franc CFA (XOF)</option>
                    <option value="EUR" {{ old('devise', $devis->devise ?? '') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                    <option value="USD" {{ old('devise', $devis->devise ?? '') == 'USD' ? 'selected' : '' }}>Dollar (USD)</option>
                </select>
            </div>
        </div>
        

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Désignations</h4>
                        <div class="email-repeater mb-3">
                            <div data-repeater-list="designations">
                                @foreach($devis->details as $detail)
                                    <div data-repeater-item class="row mb-3">
                                        <div class="col-md-3 mt-3 mt-md-0">
                                            <select class="select2 form-control designation" name="designations[][description]">
                                                <option value="">Sélectionner</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->description }}" 
                                                            data-id="{{ $designation->id }}" 
                                                            data-price="{{ $designation->prix_unitaire }}"
                                                            {{ $designation->id == $detail->designation_id ? 'selected' : '' }}>
                                                        {{ $designation->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="designations[][id]" class="designation-id" value="{{ $detail->designation_id }}">
                                        </div>
                        
                                        <div class="col-md-2 mt-3 mt-md-0">
                                            <input type="number" class="form-control quantity" name="designations[][quantity]" placeholder="Qte" value="{{ $detail->quantite }}" min="1">
                                        </div>
                        
                                        <div class="col-md-2 mt-3 mt-md-0">
                                            <input type="number" class="form-control price" name="designations[][price]" placeholder="PU" value="{{ $detail->prix_unitaire }}">
                                        </div>
                        
                                        <div class="col-md-2 mt-3 mt-md-0">
                                            <input type="number" class="form-control discount" name="designations[][discount]" placeholder="Remise" value="{{ $detail->remise }}" min="0">
                                        </div>
                        
                                        <div class="col-md-2 mt-3 mt-md-0">
                                            <input type="number" class="form-control total" name="designations[][total]" placeholder="Total" value="{{ $detail->total }}" readonly>
                                        </div>
                        
                                        <div class="col-md-1 mt-3 mt-md-0">
                                            <button data-repeater-delete class="btn bg-danger-subtle text-danger" type="button">
                                                <i class="ti ti-x fs-5 d-flex"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        
                            <button type="button" data-repeater-create class="btn bg-primary-subtle text-primary">
                                <span class="fs-4 me-1">+</span> Ajouter une autre
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
                                <input type="number" class="form-control total-ht" name="total-ht" value="{{ old('total-ht', session('data.total-ht', 0)) }}" readonly>

                                
                            </div>
                            <div class="col-4">
                                <label class="form-label">TVA (%) : <span class="text-danger">*</span></label>
                                <input type="number" class="form-control tva" name="tva"  value="{{ old('tva', session('data.tva', 18)) }}" readonly>
                                
                            </div>
                            <div class="col-4">
                                <div class="mb-4">
                                    <label class="form-label">Total TTC <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control total-ttc"  name="total-ttc"  value="{{ old('total-ttc', session('data.total-ttc', 0)) }}" readonly>
                                </div>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Acompte <span class="text-danger">*</span></label>
                                <input type="number" class="form-control acompte"  name="acompte"  value="{{ old('acompte', session('data.acompte', 0)) }}">


                               
                               
                            </div>
                            <div class="col-4">
                                <label class="form-label">Solde <span class="text-danger">*</span></label>
                                <input type="number" class="form-control solde"  name="solde"  value="{{ old('solde', session('data.solde', 0)) }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        

        <div class="form-actions mb-5">
            <button type="submit" class="btn btn-success">Suivant</button>
        </div>
    </form>
</section>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
    function updateTotal(row) {
        var price = parseFloat(row.find('.price').val()) || 0;
        var quantity = parseInt(row.find('.quantity').val()) || 1;
        var discount = parseFloat(row.find('.discount').val()) || 0;

        var total = (price * quantity) - discount;
        if (total < 0) total = 0; // Empêcher un total négatif

        row.find('.total').val(total.toFixed(2)); // Afficher avec 2 décimales
        updateTotalHT();
    }

    // Mise à jour du prix unitaire lorsqu'on sélectionne une désignation
    $(document).on('change', '.designation', function () {
        var selectedOption = $(this).find(':selected');
        var price = parseFloat(selectedOption.data('price')) || 0;
        var row = $(this).closest('.row');

        row.find('.price').val(price); // Mettre à jour le prix unitaire
        updateTotal(row);
    });

    // Mise à jour du total lorsqu'on modifie quantité ou remise
    $(document).on('input', '.quantity, .discount', function () {
        var row = $(this).closest('.row');
        updateTotal(row);
    });

    // Fonction pour mettre à jour Total HT
    function updateTotalHT() {
        var totalHT = 0;
        $('.email-repeater .row').each(function () {
            var row = $(this);
            var total = parseFloat(row.find('.total').val()) || 0;
            totalHT += total;
        });

        // Mise à jour de Total HT
        $('.total-ht').val(totalHT.toFixed(2));

        updateTVAandTTC(totalHT);
    }

    // Fonction pour mettre à jour TVA et Total TTC
    function updateTVAandTTC(totalHT) {
        var tvaRate = 18; // TVA 18%
        var tvaValue = (totalHT * tvaRate) / 100; // Valeur de la TVA
        var totalTTC = totalHT + tvaValue; // Calcul correct du Total TTC

        // Mise à jour de la TVA (valeur fixe à 18)
        $('.tva').val(tvaRate.toFixed(2)); // TVA en pourcentage

        // Mise à jour de Total TTC
        $('.total-ttc').val(totalTTC.toFixed(2));

        updateSolde(totalTTC);
    }

    // Fonction pour mettre à jour le solde
    function updateSolde(totalTTC) {
        var acompte = parseFloat($('.acompte').val()) || 0;
        var solde = totalTTC - acompte;

        // Mise à jour du solde
        $('.solde').val(solde.toFixed(2));
    }

    // Quand l'acompte change, mettre à jour le solde
    $(document).on('input', '.acompte', function () {
        var totalTTC = parseFloat($('.total-ttc').val()) || 0;
        updateSolde(totalTTC);
    });

    // Chaque fois qu'une ligne est ajoutée
    $(document).on('click', '[data-repeater-create]', function () {
        updateTotalHT();
    });

    // Chaque fois qu'une ligne est supprimée
    $(document).on('click', '[data-repeater-delete]', function () {
        updateTotalHT();
    });

});

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" /> --}}
<script>
    $(document).ready(function () {
        // Initialiser le répéteur
        $('.email-repeater').repeater({
            initEmpty: false,
            defaultValues: {},
            show: function () {
                $(this).slideDown();
                initializeSelect2($(this)); 
                $(this).find('.discount').val(0); 
                $(this).find('.quantity').val(1);
                updateRowData($(this));
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });
    
        // Fonction pour initialiser Select2
        function initializeSelect2(container) {
            container.find('.designation').select2({
                width: '100%',
                placeholder: "Sélectionner",
                allowClear: true
            });
        }
    
        // Mettre à jour les champs cachés lors du chargement des données
        function updateRowData(row) {
            let select = row.find('.designation');
            let selectedOption = select.find('option:selected');
    
            if (selectedOption.val() !== "") {
                row.find('.designation-id').val(selectedOption.data('id'));
                row.find('.price').val(selectedOption.data('price') || 0);
                row.find('.total').val(computeTotal(row));
            }
        }
    
        // Calcul du total automatiquement
        function computeTotal(row) {
            let quantity = parseFloat(row.find('.quantity').val()) || 0;
            let price = parseFloat(row.find('.price').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;
            return ((quantity * price) - discount).toFixed(2);
        }
    
        // Appliquer Select2 aux éléments existants
        initializeSelect2($(document));
    
        // Mettre à jour les valeurs existantes à l'affichage
        $('[data-repeater-item]').each(function () {
            updateRowData($(this));
        });
    
        // Gérer le changement de sélection
        $(document).on('change', '.designation', function () {
            let row = $(this).closest('[data-repeater-item]');
            updateRowData(row);
        });
    
        // Mettre à jour le total lors de la modification des champs
        $(document).on('input', '.quantity, .price, .discount', function () {
            let row = $(this).closest('[data-repeater-item]');
            row.find('.total').val(computeTotal(row));
        });
    });
    </script>
    
@endpush