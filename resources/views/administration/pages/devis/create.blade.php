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
   <div class="container">
    <form action="{{ route('dashboard.devis.recap') }}" method="POST">
        @csrf
       
            <div class="row">
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
                                                    {{ old('client_id', session('data.client_id')) == $client->id ? 'selected' : '' }}>
                                                    {{ $client->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="button" class="btn bg-warning-subtle text-warning px-4 fs-4 " data-bs-toggle="modal" data-bs-target="#addContactModal">
                                            <i class="ti ti-users text-white me-1 fs-5"></i> 
                                            Ajouter
                                          </button>
                                    </div>
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
                                    <div class="">
                                        <label class="form-label">Date d'Émission</label>
                                        <input type="date" name="date_emission" value="{{ old('date_emission', session('data.date_emission')) }}" class="form-control mydatepicker">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="form-label">Date d'Échéance</label>
                                        <input type="date" name="date_echeance" value="{{ old('date_echeance', session('data.date_echeance')) }}" class="form-control mydatepicker">
                                    </div>
                                </div>
                            </div>
                            

                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Dévise</h4>
                    <div class="col-lg-6">
                        <select name="devise" class="form-control">
                            @php
                                $deviseUser = Auth::user()->pays->devise ?? 'XOF'; // Devise par défaut si non définie
                            @endphp
                            
                            <option value="XOF" {{ $deviseUser == 'XOF' ? 'selected' : '' }}>Franc CFA (XOF)</option>
                            <option value="XAF" {{ $deviseUser == 'XAF' ? 'selected' : '' }}>Franc Guinéen (XAF)</option>
                            <option value="EUR" {{ $deviseUser == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                            <option value="USD" {{ $deviseUser == 'USD' ? 'selected' : '' }}>Dollar (USD)</option>
                        </select>
                    </div>

                    <div class="col-lg-6">
                        <input type="number" name="taux">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                        <h4 class="card-title mb-7">Désignations</h4>
            
                        <div class="email-repeater mb-3">
                            <div data-repeater-list="designations">
                                <div data-repeater-item class="row mb-3">
                                    <div class="col-md-3 mt-3 mt-md-0">
                                        <select class="select2 form-control designation" name="designations[][description]">
                                            <option value="">Sélectionner</option>
                                            @foreach ($designations as $designation)
                                                <option value="{{ $designation->description }}" data-id="{{ $designation->id }}" data-price="{{ $designation->prix_unitaire }}">
                                                    {{ $designation->description }}
                                                </option>

                                            @endforeach
                                        </select>
                                        <input type="hidden" name="designations[][id]" class="designation-id">


                                    </div>
                                    <div class="col-md-2 mt-3 mt-md-0">

                                        <input type="number" class="form-control quantity" name="designations[][quantity]" placeholder="Qte" value="1" min="1">
                                    </div>
                                    <div class="col-md-2 mt-3 mt-md-0">
                                        <input type="number" class="form-control price" name="designations[][price]" placeholder="PU">
                                    </div>
                                    <div class="col-md-2 mt-3 mt-md-0">
                                        <input type="number" class="form-control discount" name="designations[][discount]" placeholder="Remise" value="0" min="0">
                                    </div>
                                    <div class="col-md-2 mt-3 mt-md-0">
                                        
                                        <input type="text" class="form-control total" name="designations[][total]" placeholder="Total" readonly>
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
                                    <div class="">
                                        <label class="form-label">Commande (%)</label>
                                        <input type="number" name="commande" class="form-control 
                                            @error('commande') is-invalid @enderror" 
                                            value="{{ old('commande', session('data.commande', 0)) }}" id="commande" max="100">
                                        @error('commande')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="form-label">Livraison (%)</label>
                                        <input type="number" name="livraison" class="form-control 
                                            @error('livraison') is-invalid @enderror" 
                                            value="{{ old('livraison', session('data.livraison', 0)) }}" id="livraison" max="100">
                                        @error('livraison')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="form-label">Validité de l'offre</label>
                                        <input type="number" name="validite" class="form-control mydatepicker 
                                            @error('validite') is-invalid @enderror" 
                                            value="{{ old('validite', session('data.validite')) }}">
                                        @error('validite')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="form-label">Délai de livraison</label>
                                        <input type="number" name="delai" class="form-control mydatepicker 
                                            @error('delai') is-invalid @enderror" 
                                            value="{{ old('delai', session('data.delai')) }}">
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
                                    <option value="{{ $banque->id }}" 
                                        @if(old('banque_id', session('data.banque_id')) == $banque->id) selected @endif>
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
                                    <div class="input-group">
                                        <input type="number" class="form-control tva" name="tva" value="{{ old('tva', session('data.tva', 18)) }}" readonly>
                                        <div class="input-group-text">
                                            <input type="checkbox" class="toggle-tva">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-4">
                                    <div class="mb-4">
                                        <label class="form-label">Total TTC <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control total-ttc"  name="total-ttc"  value="{{ old('total-ttc', session('data.total-ttc', 0)) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Acompte <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control acompte" id="acompte" name="acompte" value="{{ old('acompte', session('data.acompte', 0)) }}" readonly>
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
                <button type="submit" class="btn btn-success">Recapitulaif</button>
            </div>

    </form>

    <div class="modal fade" id="addContactModal" tabindex="-1" role="dialog" aria-labelledby="addContactModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h5 class="modal-title">Information du Client</h5>
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
                    <form id="addContactModalTitle">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3 contact-name">
                                <input type="text" id="c-name" class="form-control" placeholder="Nom ou raison sociale">
                                <span class="validation-text text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 contact-occupation">
                                <input type="text" id="c-occupation" class="form-control" placeholder="N°CC">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 contact-phone">
                                <input type="text" id="c-phone" class="form-control" placeholder="Téléphone">
                                <span class="validation-text text-danger"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 contact-occupation">
                            <input type="text" id="c-adresse" class="form-control" placeholder="Adresse">
                            </div>
                        </div>
                        </div>

                        <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 contact-phone">
                            <input type="text" id="c-ville" class="form-control" placeholder="Ville">
                            <span class="validation-text text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 contact-phone">
                            <input type="text" id="c-attn" class="form-control" placeholder="ATTM">
                            <span class="validation-text text-danger"></span>
                            </div>
                        </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <div class="d-flex gap-6 m-0">
                <button id="btn-add" class="btn btn-success">Ajouter</button>
                <button class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal"> Annuler</button>
            </div>
            </div>
        </div>
        </div>
    </div>
   </div>

</section>
@endsection

@push('scripts')

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
        // Fonction pour mettre à jour le total d'une ligne
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
            var totalTTC = parseFloat($('.total-ttc').val()) || 0; // Récupérer le total TTC
            var commande = parseFloat($('#commande').val()) || 0; // Pourcentage de commande
            var acompte = (totalTTC * commande) / 100; // Calcul de l'acompte

            $('.acompte').val(acompte.toFixed(2)); // Mettre à jour l'acompte
            updateSolde(totalTTC); // Mettre à jour le solde
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
            updateAcompte(); // Recalculer l'acompte quand la commande change
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
            let price = selectedOption.data('price') || 0;
    
            let row = $(this).closest('[data-repeater-item]');
            row.find('.designation-id').val(id);
            row.find('.price').val(price).trigger('input');
        });
    
        // Calcul automatique du total pour chaque ligne
        $(document).on('input', '.quantity, .price, .discount', function () {
            let row = $(this).closest('[data-repeater-item]');
            let quantity = parseFloat(row.find('.quantity').val()) || 0;
            let price = parseFloat(row.find('.price').val()) || 0;
            let discount = parseFloat(row.find('.discount').val()) || 0;
    
            let total = (quantity * price) - discount;
            row.find('.total').val(total.toFixed(2));
    
            // Mettre à jour le Total HT après chaque modification
            updateTotalHT();
        });
    
        // Fonction pour mettre à jour Total HT
        function updateTotalHT() {
            let totalHT = 0;
            $('.email-repeater [data-repeater-item]').each(function () {
                let total = parseFloat($(this).find('.total').val()) || 0;
                totalHT += total;
            });
    
            $('.total-ht').val(totalHT.toFixed(2));
            updateTVAandTTC(); // Mettre à jour TVA et Total TTC
        }
    
        // Fonction pour mettre à jour TVA et Total TTC
        function updateTVAandTTC() {
            let totalHT = parseFloat($('.total-ht').val()) || 0;
            let tvaRate = parseFloat($('.tva').val()) || 0;
            let tvaValue = (totalHT * tvaRate) / 100;
            let totalTTC = totalHT + tvaValue;
    
            $('.total-ttc').val(totalTTC.toFixed(2));
            updateAcompte(); // Mettre à jour l'acompte après chaque modification de TTC
        }
    
        // Fonction pour mettre à jour l'acompte
        function updateAcompte() {
            let totalTTC = parseFloat($('.total-ttc').val()) || 0; // Récupérer le total TTC
            let commande = parseFloat($('#commande').val()) || 0; // Pourcentage de commande
            let acompte = (totalTTC * commande) / 100; // Calcul de l'acompte
    
            $('.acompte').val(acompte.toFixed(2)); // Mettre à jour l'acompte
            updateSolde(totalTTC); // Mettre à jour le solde
        }
    
        // Fonction pour mettre à jour le solde
        function updateSolde(totalTTC) {
            let acompte = parseFloat($('.acompte').val()) || 0;
            let solde = totalTTC - acompte;
    
            $('.solde').val(solde.toFixed(2));
        }
    
        // Quand l'acompte change, mettre à jour le solde
        $(document).on('input', '.acompte', function () {
            updateSolde(parseFloat($('.total-ttc').val()) || 0);
        });
    
        // Quand le pourcentage de commande change, mettre à jour l'acompte
        $(document).on('input', '#commande', function () {
            updateAcompte(); // Recalculer l'acompte quand la commande change
        });
    
        // Activation/désactivation de la TVA en fonction de la case à cocher
        $(document).on('change', '.toggle-tva', function () {
            let tvaInput = $('.tva');
    
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


@endpush
