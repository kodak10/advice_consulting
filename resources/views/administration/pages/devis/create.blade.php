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
                    <!-- Étape Dates -->
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
                                    <div class="">
                                        <label class="form-label">Commande (%)</label>
                                        <input type="number" name="commande" class="form-control mydatepicker 
                                            @error('commande') is-invalid @enderror" 
                                            value="{{ old('commande', session('data.commande')) }}">
                                        @error('commande')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="">
                                        <label class="form-label">Livraison (%)</label>
                                        <input type="number" name="livraison" class="form-control mydatepicker 
                                            @error('livraison') is-invalid @enderror" 
                                            value="{{ old('livraison', session('data.livraison')) }}">
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
                                    <input type="number" class="form-control tva"  name="tva"  value="{{ old('tva', session('data.tva', 18)) }}" readonly>
                                    
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

</section>
@endsection

@push('styles')

@endpush
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

<script>
    document.getElementById('btn-add').addEventListener('click', function(event) {
      event.preventDefault();
  
      // Récupérer les données du formulaire
      let formData = {
          nom: document.getElementById('c-name').value,
          numero_cc: document.getElementById('c-occupation').value,
          telephone: document.getElementById('c-phone').value,
          adresse: document.getElementById('c-adresse').value,
          ville: document.getElementById('c-ville').value,
          attn: document.getElementById('c-attn').value,
      };
  
      // Envoi de la requête avec fetch
      fetch("{{ route('dashboard.clients.store') }}", {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
          },
          body: JSON.stringify(formData)
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              // Afficher le toast de succès
              toastr.success(data.message, 'Succès', {
                  positionClass: 'toast-top-right',
                  timeOut: 5000,
                  closeButton: true,
                  progressBar: true,
              });
  
              // Fermer le modal et recharger la page après un délai
              $('#addContactModal').modal('hide');
              setTimeout(() => {
                  location.reload();  // Recharger la page pour afficher les nouveaux clients
              }, 5000);
          } else {
              // Afficher le toast d'erreur lorsque data.success est false
              toastr.error(data.message, 'Erreur', {
                  positionClass: 'toast-top-right',
                  timeOut: 20000,
                  closeButton: true,
                  progressBar: true,
              });
              // Vous pouvez également choisir de recharger la page ou non
              setTimeout(() => {
                  location.reload();
              }, 1000);
          }
      })
      .catch(error => {
          console.error('Erreur:', error);
          // En cas d'erreur réseau ou autre, afficher un toast d'erreur générique
          toastr.error("Une erreur est survenue lors de l'ajout du client.", 'Erreur', {
              positionClass: 'toast-top-right',
              timeOut: 20000,
              closeButton: true,
              progressBar: true,
          });
          setTimeout(() => {
              location.reload();
          }, 1000);
      });
  });
  
  </script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" /> --}}

<script>
$(document).ready(function () {
    // Initialisation du répéteur
    $('.email-repeater').repeater({
        initEmpty: false,
        defaultValues: {},
        show: function () {
            $(this).slideDown();
            initializeSelect2($(this)); // Réinitialiser Select2 pour les nouveaux éléments
            $(this).find('.discount').val(0);
            $(this).find('.quantity').val(0);

        },
        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    });

    // Fonction pour initialiser Select2
    function initializeSelect2(container) {
        container.find('.designation').select2({
            width: '100%', // Corrige le problème d'affichage dans les répétitions
            placeholder: "Sélectionner",
            allowClear: true
        });
    }

    // Appliquer Select2 aux éléments existants
    initializeSelect2($(document));

    // Gestion du changement de sélection pour récupérer les données associées
    $(document).on('change', '.designation', function () {
        let selectedOption = $(this).find(':selected');
        let id = selectedOption.data('id');
        let price = selectedOption.data('price') || 0;
        
        let row = $(this).closest('[data-repeater-item]');
        row.find('.designation-id').val(id);
        row.find('.price').val(price).trigger('input');
    });

    // Calcul automatique du total
    $(document).on('input', '.quantity, .price, .discount', function () {
        let row = $(this).closest('[data-repeater-item]');
        let quantity = parseFloat(row.find('.quantity').val()) || 0;
        let price = parseFloat(row.find('.price').val()) || 0;
        let discount = parseFloat(row.find('.discount').val()) || 0;

        let total = (quantity * price) - discount;
        row.find('.total').val(total.toFixed(2));
    });
});
</script>


@endpush
