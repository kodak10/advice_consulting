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

    <div class="container-fluid">
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
                    
                    <div class="row">
                        <div class="col-lg-6">
                            <select id="devise" name="devise" class="form-control">
                                @php
                                    // Récupérer la devise stockée dans le devis lors de l'édition
                                    $deviseDevis = $devis->devise ?? (Auth::user()->pays->devise ?? 'XOF'); // Devise du devis si présente, sinon devise de l'utilisateur
                                @endphp
                                
                                <option value="XOF" {{ $deviseDevis == 'XOF' ? 'selected' : '' }}>Franc CFA (XOF)</option>
                                <option value="EUR" {{ $deviseDevis == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                <option value="USD" {{ $deviseDevis == 'USD' ? 'selected' : '' }}>Dollar (USD)</option>
                                <option value="GBP" {{ $deviseDevis == 'GBP' ? 'selected' : '' }}>Livre Sterling (GBP)</option>
                                <option value="GNF" {{ $deviseDevis == 'GNF' ? 'selected' : '' }}>Franc Guinéen (GNF)</option>
                            </select>
                        </div>
                        
                        <div class="col-lg-6">
                            <div class="input-group devise">
                                <!-- Le champ de saisie est désactivé par défaut (readonly) -->
                                <input type="number" id="taux" name="taux" value= "{{ old('taux', $devis->taux ?? 1) }}" readonly class="form-control">
                                
                                <!-- Le bouton checkbox pour activer/désactiver l'édition -->
                                <div class="input-group-text">
                                    <input type="checkbox" id="toggle-taux" class="toggle-taux">
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
                            
                                            <div class="col-md-1 mt-3 mt-md-0">
                                                <input type="number" class="form-control quantity" name="designations[][quantity]" placeholder="Qte" value="{{ $detail->quantite }}" min="1">
                                            </div>
                            
                                            <div class="col-md-2 mt-3 mt-md-0">
                                                <input type="number" step="0.01" class="form-control price" name="designations[][price]" placeholder="PU" value="{{ $detail->prix_unitaire }}">
                                            </div>
                            
                                            <div class="col-md-1 mt-3 mt-md-0">
                                                <input type="number" step="0.01" class="form-control discount" name="designations[][discount]" placeholder="Remise" value="{{ $detail->remise }}" min="0">
                                            </div>

                                            <div class="col-md-2 mt-3 mt-md-0">
                                                <input type="number" step="0.01" class="form-control net-price" name="designations[][net_price]" placeholder="Remise" value="{{ $detail->net_price }}" min="0">
                                            </div>
                            
                                            <div class="col-md-2 mt-2 mt-md-0">
                                                <input type="number" step="0.01" class="form-control total" name="designations[][total]" placeholder="Total" value="{{ $detail->total }}" readonly>
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
                                        <input type="number" name="commande" id="commande" max="100" class="form-control mydatepicker @error('commande') is-invalid @enderror" 
                                            value="{{ $devis->commande }}">
                                        @error('commande')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div>
                                        <label class="form-label">Livraison</label>
                                        <input type="text" name="livraison" id="livraison" max="100" class="form-control mydatepicker @error('livraison') is-invalid @enderror" 
                                            value="{{ $devis->livraison }}">
                                        @error('livraison')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div>
                                        <label class="form-label">Validité de l'offre (jours)</label>
                                        <input type="number" name="validite" class="form-control mydatepicker @error('validite') is-invalid @enderror" 
                                            value="{{ $devis->validite }}">
                                        @error('validite')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div>
                                        <label class="form-label">Délai de livraison(jours)</label>
                                        <input type="number" name="delai" class="form-control mydatepicker @error('delai') is-invalid @enderror" 
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
                                    <option value="{{ $banque->id }}" {{ old('banque_id', $devis->banque_id ?? '') == $banque->id ? 'selected' : '' }}>
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
                                    <input type="number" class="form-control total-ht" name="total-ht" value="{{ old('total-ht', session('data.total-ht', $devis->total_ht ?? 0)) }}" readonly>

                                    
                                </div>
                                {{-- <div class="col-4">
                                    <label class="form-label">TVA (%) : <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control tva" name="tva"  value="{{ old('tva', session('data.tva', 18)) }}" readonly>
                                    
                                </div> --}}

                                <div class="col-4">
                                    <label class="form-label">TVA (%) : <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control tva" name="tva" value="{{ old('tva', session('data.tva', $devis->tva ?? 0)) }}"
                                        readonly>
                                        <div class="input-group-text">
                                            <input type="checkbox" class="toggle-tva">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="mb-4">
                                        <label class="form-label">Total TTC <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control total-ttc"  name="total-ttc"  value="{{ old('total-ttc', session('data.total-ttc', $devis->total_ttc ?? 0)) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Acompte <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control acompte"  name="acompte"  value="{{ old('acompte', session('data.acompte', $devis->acompte ?? 0)) }}">


                                
                                
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Solde <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control solde"  name="solde"  value="{{ old('solde', session('data.solde', $devis->solde ?? 0)) }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            

            <div class="form-actions mb-5">
                <input type="hidden" name="texte" value="{{ old('texte', $devis->texte ?? 0) }}">
                <button type="submit" class="btn btn-success">Suivant</button>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
{{-- <script>
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

     // Activation/désactivation de la TVA en fonction de la case à cocher
     $(document).on('change', '.toggle-tva', function () {
            var tvaInput = $('.tva');

            if ($(this).is(':checked')) {
                tvaInput.prop('readonly', false).val(0);
            } else {
                tvaInput.prop('readonly', true).val(18);
            }

            updateTVAandTTC();
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

</script> --}}


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<script>
    let timeout;

    // Fonction pour mettre à jour l'autre champ
    function updateFields() {
        // Clear the timeout to reset the delay each time
        clearTimeout(timeout);

        // Set a new timeout to delay the update
        timeout = setTimeout(function() {
            let commande = parseFloat(document.getElementById('commande').value) || 0;
            let livraison = parseFloat(document.getElementById('livraison').value) || 0;

            // Limiter les valeurs à 100 maximum
            if (commande > 100) {
                commande = 100;
                document.getElementById('commande').value = commande;
            }
            if (livraison > 100) {
                livraison = 100;
                document.getElementById('livraison').value = livraison;
            }

            // Si l'utilisateur modifie le champ "commande"
            if (document.activeElement.id === 'commande') {
                console.log("Commande modifiée:", commande); // Debug
                // Si commande est 100, mettre livraison à 0
                if (commande === 100) {
                    document.getElementById('livraison').value = 0;
                } else {
                    // Calculer la valeur restante pour livraison
                    livraison = 100 - commande;
                    if (livraison < 0) livraison = 0; // Empêcher la valeur négative
                    document.getElementById('livraison').value = livraison;
                }
            }

            // Si l'utilisateur modifie le champ "livraison"
            if (document.activeElement.id === 'livraison') {
                console.log("Livraison modifiée:", livraison); // Debug
                // Si livraison est 100, mettre commande à 0
                if (livraison === 100) {
                    document.getElementById('commande').value = 0;
                } else {
                    // Calculer la valeur restante pour commande
                    commande = 100 - livraison;
                    if (commande < 0) commande = 0; // Empêcher la valeur négative
                    document.getElementById('commande').value = commande;
                }
            }

            // Si l'un des champs est vide (0), réinitialiser l'autre champ à 0
            if (commande === 0) {
                document.getElementById('livraison').value = 0;
            }
            if (livraison === 0) {
                document.getElementById('commande').value = 0;
            }

            // Mettre à jour l'acompte après chaque modification
            updateAcompte();
        }, 100); // Délai de 100ms pour éviter les mises à jour trop rapides
    }

    // Ajouter un écouteur d'événements sur les deux champs pour mettre à jour l'autre après un délai
    document.getElementById('commande').addEventListener('input', updateFields);
    document.getElementById('livraison').addEventListener('input', updateFields);

    // Fonction pour mettre à jour l'acompte
    function updateAcompte() {
        var totalTTC = parseFloat($('.total-ttc').val()) || 0; // Récupérer le total TTC
        var commande = parseFloat($('#commande').val()) || 0; // Pourcentage de commande
        console.log("Total TTC:", totalTTC);  // Debug
        console.log("Commande (%):", commande); // Debug
        var acompte = (totalTTC * commande) / 100; // Calcul de l'acompte
        console.log("Acompte calculé:", acompte); // Debug

        $('.acompte').val(acompte.toFixed(2)); // Mettre à jour l'acompte
        updateSolde(totalTTC); // Mettre à jour le solde
    }

    // Fonction pour mettre à jour le solde
    function updateSolde(totalTTC) {
        var acompte = parseFloat($('.acompte').val()) || 0;
        var solde = totalTTC - acompte;
        console.log("Solde calculé:", solde); // Debug

        $('.solde').val(solde.toFixed(2));
    }
</script>


<script>
    $(document).ready(function () {
       function formatNumber(value, devise) {
            if (devise === 'XOF') {
                return Math.round(value); // Arrondir à l'entier pour XOF
            }
            return value.toFixed(2); // 2 décimales pour les autres devises
        }
        // Fonction pour mettre à jour le total d'une ligne
        function updateTotal(row) {
            var devise = $('#devise').val();
            var price = parseFloat(row.find('.price').val()) || 0;
            var quantity = parseInt(row.find('.quantity').val()) || 1;
            var discount = parseFloat(row.find('.discount').val()) || 0;
            

            //var discountPercent = parseFloat(row.find('.discount').val()) || 0;
            
            // Calcul du prix unitaire net après remise
            var netPrice = price * (1 - discount / 100);
            row.find('.net-price').val(formatNumber(netPrice, devise));



            var total = netPrice * quantity;
            if (total < 0) total = 0; // Empêcher un total négatif

            row.find('.total').val(total.toFixed(2)); // Afficher avec 2 décimales
            updateTotalHT();
        }

        // Fonction pour mettre à jour le prix en fonction du taux
        function updatePriceWithTaux(row) {
            var selectedOption = row.find('.designation').find(':selected');
            var priceOriginal = parseFloat(selectedOption.data('price')) || 0;
            var taux = parseFloat($('#taux').val()) || 1.0;

            var priceConverted = priceOriginal / taux; // Diviser par le taux
            row.find('.price').val(priceConverted.toFixed(2)); // Mettre à jour le champ .price
            updateTotal(row); // Recalculer le total
        }

        // Mise à jour du prix unitaire lorsqu'on sélectionne une désignation
        $(document).on('change', '.designation', function () {
            var row = $(this).closest('[data-repeater-item]');
            updatePriceWithTaux(row); // Mettre à jour le prix avec le taux
        });

        // Mise à jour du total lorsqu'on modifie quantité ou remise
        $(document).on('input', '.quantity, .discount, .price', function () {
            var row = $(this).closest('[data-repeater-item]');
            updateTotal(row);
        });

        // Fonction pour mettre à jour Total HT
        function updateTotalHT() {
            var totalHT = 0;
            $('[data-repeater-list="designations"] [data-repeater-item]').each(function () {
                var total = parseFloat($(this).find('.total').val()) || 0;
                totalHT += total;
            });

            $('.total-ht').val(totalHT.toFixed(2));
            updateTVAandTTC();
        }

        // Fonction pour mettre à jour TVA et Total TTC
        function updateTVAandTTC() {
            var totalHT = parseFloat($('.total-ht').val()) || 0;
            var tvaRate = parseFloat($('.tva').val()) || 0;
            var tvaValue = (totalHT * tvaRate) / 100;
            var totalTTC = totalHT + tvaValue;

            $('.total-ttc').val(totalTTC.toFixed(2));
            updateAcompte(); // Mettre à jour l'acompte après chaque modification de TTC
        }

        // Fonction pour mettre à jour l'acompte
        function updateAcompte() {
            var totalTTC = parseFloat($('.total-ttc').val()) || 0;
            var commande = parseFloat($('#commande').val()) || 0;
            var acompte = (totalTTC * commande) / 100;

            $('.acompte').val(acompte.toFixed(2));
            updateSolde(totalTTC);
        }

        // Fonction pour mettre à jour le solde
        function updateSolde(totalTTC) {
            var acompte = parseFloat($('.acompte').val()) || 0;
            var solde = totalTTC - acompte;

            $('.solde').val(solde.toFixed(2));
        }

        // Quand l'acompte change, mettre à jour le solde
        $(document).on('input', '.acompte', function () {
            updateSolde(parseFloat($('.total-ttc').val()) || 0);
        });

        // Quand le pourcentage de commande change, mettre à jour l'acompte
        $(document).on('input', '#commande', function () {
            updateAcompte();
        });

        // Activation/désactivation de la TVA en fonction de la case à cocher
        $(document).on('change', '.toggle-tva', function () {
            var tvaInput = $('.tva');

            if ($(this).is(':checked')) {
                tvaInput.prop('readonly', false).val(0);
            } else {
                tvaInput.prop('readonly', true).val(18);
            }

            updateTVAandTTC();
        });

        // Recalculer la TVA et le total lorsque la TVA change
        $(document).on('input', '.tva', function () {
            updateTVAandTTC();
        });

        // Mettre à jour après ajout ou suppression d'une ligne
        $(document).on('click', '[data-repeater-create], [data-repeater-delete]', function () {
            updateTotalHT();
        });

        // Initialiser le calcul au chargement de la page
        updateTotalHT();
    });
</script>


<script>
    $(document).ready(function () {
        // Initialisation du répéteur
        $('.email-repeater').repeater({
            initEmpty: false,
            defaultValues: {},
            show: function () {
                $(this).slideDown();
                initializeSelect2($(this));
                $(this).find('.discount').val(0);
                $(this).find('.quantity').val(1);
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

        initializeSelect2($(document));

        // Mise à jour des champs lors de la sélection d'une désignation
        $(document).on('change', '.designation', function () {
            let selectedOption = $(this).find(':selected');
            let id = selectedOption.data('id');
            let priceOriginal = selectedOption.data('price') || 0;
            let taux = parseFloat($('#taux').val()) || 1.0;

            let priceConverted = priceOriginal / taux; // Diviser par le taux
            let row = $(this).closest('[data-repeater-item]');
            row.find('.designation-id').val(id);
            row.find('.price').val(priceConverted.toFixed(2)).trigger('input');
        });
    });
</script>

<script>
    const rates = @json($rates);

    // Fonction pour mettre à jour tous les prix en fonction du taux
    function updateAllPricesWithTaux() {
        const taux = parseFloat($('#taux').val()) || 1.0;
        $('[data-repeater-item]').each(function () {
            var selectedOption = $(this).find('.designation').find(':selected');
            var priceOriginal = parseFloat(selectedOption.data('price')) || 0;
            var priceConverted = priceOriginal / taux; // Diviser par le taux
            $(this).find('.price').val(priceConverted.toFixed(2)).trigger('input');
        });
    }

    $(document).on('change', '#toggle-taux', function () {
    var tauxInput = $('#taux');

    if ($(this).is(':checked')) {
        tauxInput.prop('readonly', false);
    } else {
        tauxInput.prop('readonly', true);
    }

    updateAllPricesWithTaux();
});

    // Écouteur d'événement pour le changement de devise
    $('#devise').on('change', function () {
        const selectedDevise = this.value;
        const tauxInput = $('#taux');

        if (rates[selectedDevise] !== undefined) {
            tauxInput.val(rates[selectedDevise].toFixed(2));
        } else {
            tauxInput.val(1.0); // Taux par défaut
        }

        updateAllPricesWithTaux(); // Mettre à jour tous les prix
    });

    // Écouteur d'événement pour le changement du taux de conversion
    $('#taux').on('input', function () {
        updateAllPricesWithTaux(); // Mettre à jour tous les prix
    });

    // Initialisation au chargement de la page
    // $(document).ready(function () {
    //     updateAllPricesWithTaux(); // Mettre à jour les prix au chargement
    // });
</script>
    
@endpush