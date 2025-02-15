@extends('administration.layouts.master')

@push('styles')
    <style>
    .stepper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    position: relative;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
}

.step-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #ddd;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
    margin-bottom: 5px;
    z-index: 1; /* Pour s'assurer que le numéro est au-dessus de la ligne */
}

.step-label {
    font-size: 14px;
    color: #666;
}

.step.active .step-number {
    background-color: #007bff;
    color: white;
}

.step.active .step-label {
    color: #007bff;
}

.step.completed .step-number {
    background-color: #28a745;
    color: white;
}

.step.completed .step-label {
    color: #28a745;
}

/* Ligne entre les étapes */
.stepper::before {
    content: '';
    position: absolute;
    width: calc(100% - 30px); /* Largeur totale moins la largeur des cercles */
    height: 2px;
    background-color: #ddd; /* Couleur par défaut pour les étapes non passées */
    top: 15px; /* Ajustez en fonction de la position du numéro */
    left: 15px; /* Commence au centre du premier cercle */
    z-index: 0;
}

/* Ligne colorée pour les étapes passées */
.step.completed::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 2px;
    background-color: #28a745; /* Couleur pour les étapes passées */
    top: 15px;
    left: -50%;
    z-index: 0;
}

.step:first-child::before {
    display: none; /* Pas de ligne avant la première étape */
}
    </style>

<style>
  body {
      font-family: Arial, sans-serif;
      margin: 20px;
      padding: 20px;
  }
  .invoice-container {
      width: 800px;
      border: 1px solid #000;
      padding: 20px;
      margin: auto;
  }
  .header {
      display: flex;
      justify-content: space-between;
      border-bottom: 2px solid #000;
      padding-bottom: 10px;
  }
  .info-client-container {
      display: flex;
      margin-top: 10px;
  }
  .client-container {
      width: 60%;
      display: flex;
  }
  .section-title {
      background: #f0f0f0;
      padding: 5px;
      font-weight: bold;
      width: 150px;
  }
  .client-info {
      flex-grow: 1;
      margin-left: 10px;
      border: 1px solid #ddd;
      padding: 5px;
  }
  .info {
      width: 40%;
      padding-left: 10px;
  }
  table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
  }
  th, td {
      border: 1px solid #000;
      padding: 8px;
      text-align: left;
  }
  th {
      background: #f0f0f0;
  }
  .payment-footer-container {
      display: flex;
  }
  .payment {
      flex: 2; /* Partie Banque plus large */
  }
  .footer {
      flex: 1; /* Partie Totaux plus petite */
  }
  .signature {
      margin-top: 30px;
      font-style: italic;
  }
</style>
@endpush
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
  <div class="checkout">
    <div class="card">
      <div class="card-body p-4">
        <div class="wizard-content">
              <!-- Indicateur d'étapes -->
            <!-- Indicateur d'étapes -->
<section id="stepper">
  <div class="stepper">
      <div class="step" data-step="1">
          <div class="step-number">1</div>
          <div class="step-label">Informations</div>
      </div>
      <div class="step" data-step="2">
          <div class="step-number">2</div>
          <div class="step-label">Récapitulatif</div>
      </div>
      <div class="step" data-step="3">
          <div class="step-number">3</div>
          <div class="step-label">Confirmation</div>
      </div>
  </div>
</section>

<!-- Étape 1 : Formulaire -->
<section id="step1" class="step-content">
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
                              <input type="text" id="total-ht" class="form-control total-ht" value="0.00">
                          </div>
                          <div class="col-md-6">
                              <label class="form-label">TVA 18% <span class="text-danger">*</span></label>
                              <input type="text" id="tva" class="form-control tva" value="0.18">
                          </div>
                      </div>
      
                      <div class="mb-4">
                          <label class="form-label">Total TTC <span class="text-danger">*</span></label>
                          <input type="text" id="total-ttc" class="form-control total-ttc" value="0.00">
                      </div>
      
                      <div class="row mb-4">
                          <div class="col-md-6">
                              <label class="form-label">Accompte <span class="text-danger">*</span></label>
                              <input type="text" id="accompte" class="form-control accompte" value="0.00">
                          </div>
                          <div class="col-md-6">
                              <label class="form-label">Solde <span class="text-danger">*</span></label>
                              <input type="text" id="solde" class="form-control solde" value="0.00">
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="form-actions mb-5">
          <button type="button" class="btn btn-primary" onclick="nextStep()">Suivant</button>
      </div>
  </form>
</section>

<!-- Étape 2 : Récapitulatif -->
<section id="step2" class="step-content" style="display: none;">
  <div class="invoice-container">
      <div class="header">
          <div><strong>Type règlement facture:</strong> À échéance</div>
          <div><strong>Délai:</strong> 15</div>
          <div><strong>Agent:</strong> Paul A</div>
      </div>

      <div class="info-client-container">
          <div class="client-container">
              <div class="section-title">Client</div>
              <div class="client-info">
                  <p><strong>Nom:</strong> <span id="recap-nom"></span></p>
                  <p><strong>Adresse:</strong> <span id="recap-adresse"></span></p>
                  <p><strong>Téléphone:</strong> <span id="recap-telephone"></span></p>
                  <p><strong>Ville:</strong> <span id="recap-ville"></span></p>
              </div>
          </div>

          <div class="info">
              <table>
                  <tr>
                      <td><strong>Date:</strong> <span id="recap-date-emission"></span></td>
                      <td><strong>Échéance:</strong> <span id="recap-date-echeance"></span></td>
                  </tr>
                  <tr>
                      <td><strong>N° Pro-forma:</strong> <span id="recap-numero-bc"></span></td>
                      <td></td>
                  </tr>
              </table>
          </div>
      </div>

      <!-- Détails et autres sections du récapitulatif -->
      <div class="section-title">Détails</div>
      <table>
          <tr>
              <th>Référence</th>
              <th>Description</th>
              <th>Quantité</th>
              <th>Prix unitaire</th>
              <th>TOTAL</th>
          </tr>
          <tr>
              <td colspan="5" style="text-align: center; color: red;">Cliquer ici pour saisir le titre</td>
          </tr>
      </table>

      <div class="payment-footer-container">
          <table class="payment">
              <tr>
                  <th class="section-title">Informations de paiement</th>
              </tr>
              <tr>
                  <td><strong>Banque:</strong> VERSUS BANK</td>
              </tr>
              <tr>
                  <td><strong>Compte:</strong> C112 01001 012206440008 24</td>
              </tr>
          </table>

          <table class="footer">
              <tr>
                  <th class="section-title">Totaux</th>
              </tr>
              <tr>
                  <td><strong>Total HT:</strong> <span id="recap-total-ht"></span></td>
              </tr>
              <tr>
                  <td><strong>TVA 18%:</strong> <span id="recap-tva"></span></td>
              </tr>
              <tr>
                  <td><strong>Total TTC:</strong> <span id="recap-total-ttc"></span></td>
              </tr>
              <tr>
                  <td><strong>Acompte:</strong> <span id="recap-accompte"></span></td>
              </tr>
              <tr>
                  <td><strong>Solde:</strong> <span id="recap-solde"></span></td>
              </tr>
          </table>
      </div>

      <div class="signature">
          <p><strong>Cachet et signature:</strong></p>
          <p>"Je sers Khalil, vous passez à la facturation."</p>
      </div>
  </div>

  <div class="form-actions mb-5">
      <button type="button" class="btn btn-primary" onclick="prevStep()">Précédent</button>
      <button type="button" class="btn btn-primary" onclick="nextStep()">Suivant</button>
  </div>
</section>

<!-- Étape 3 : Confirmation -->
<section id="step3" class="step-content" style="display: none;">
  <div class="confirmation-message">
      <p>Vos informations ont été enregistrées avec succès.</p>
      <button type="button" class="btn btn-primary" onclick="prevStep()">Précédent</button>
      <button type="button" class="btn btn-primary" onclick="generatePDF()">Enregisrer & Générer PDF</button>
  </div>
</section>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('adminAssets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
<script src="{{ asset('adminAssets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>

<script src="{{ asset('adminAssets/js/forms/form-wizard.js') }}"></script>
<script src="{{ asset('adminAssets/js/apps/ecommerce.js') }}"></script>

<script>
  $(document).ready(function(){
      $('#client-select').change(function() {
          // Récupérer l'option sélectionnée
          var selectedOption = $(this).find('option:selected');
          
          // Extraire les données de l'option
          var nom = selectedOption.data('nom');
          // var adresse = selectedOption.data('adresse');
          // var telephone = selectedOption.data('telephone');
          // var cc = selectedOption.data('cc');
          // var nttn = selectedOption.data('nttn');
          
          // Mettre à jour les champs de texte
          $('#client-nom').val(nom);
          // $('#client-adresse').val(adresse);
          // $('#client-telephone').val(telephone);
          // $('#client-cc').val(cc);
          // $('#client-nttn').val(nttn);
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

<script>
  let currentStep = 1;

// Fonction pour mettre à jour l'indicateur d'étapes (stepper)
function updateStepper() {
    const steps = document.querySelectorAll('.step');
    steps.forEach((step, index) => {
        if (index + 1 < currentStep) {
            step.classList.add('completed');
            step.classList.remove('active');
        } else if (index + 1 === currentStep) {
            step.classList.add('active');
            step.classList.remove('completed');
        } else {
            step.classList.remove('active', 'completed');
        }
    });
}

// Fonction pour passer à l'étape suivante
function nextStep() {
    if (currentStep === 1) {
        const clientId = document.getElementById('client-select').value;

        console.log("ID du client sélectionné :", clientId); // Debug

        if (!clientId || clientId === "none") {
            alert("Veuillez sélectionner un client.");
            return;
        }


        const designations = [];
                const rows = document.querySelectorAll('[data-repeater-item]');
                
                rows.forEach(row => {
                    const designation = row.querySelector('.designation-select').value;
                    const quantity = row.querySelector('.quantity').value;
                    const pu = row.querySelector('.pu').value;
                    const total = row.querySelector('.total').value;

                    // Vérifier que les champs sont bien remplis
                    if (designation && quantity && pu) {
                        designations.push({
                            designation: designation,
                            quantity: quantity,
                            pu: pu,
                            total: total || (quantity * pu) // Calcul du total si non fourni
                        });
                    }
                });

                if (designations.length === 0) {
                    alert("Veuillez remplir au moins une désignation.");
                    return;
                }

                // Passer les données à l'étape suivante
                displayStep2(designations);

        // Envoyer une requête AJAX pour récupérer les informations du client
        fetch(`/dashboard/client/${clientId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                // Afficher les informations du client à l'étape 2
                document.getElementById('recap-nom').textContent = data.nom;
                document.getElementById('recap-adresse').textContent = data.adresse;
                document.getElementById('recap-telephone').textContent = data.telephone;
                document.getElementById('recap-ville').textContent = data.ville;

                document.getElementById('date-emission').textContent = data.date_emission;
                document.getElementById('date-echeance').textContent = data.dateEcheance;
               
                

                // Passer à l'étape 2
                document.getElementById('step1').style.display = 'none';
                document.getElementById('step2').style.display = 'block';
                currentStep++;
                updateStepper();
            })
            .catch(error => {
                console.error('Erreur lors de la récupération des informations du client:', error);
                alert("Une erreur s'est produite lors de la récupération des informations du client.");
            });
    } else if (currentStep === 2) {
        // Étape 2 : Mettre à jour le récapitulatif avec les données du formulaire
        updateRecap();

        // // Réinitialiser le tableau pour supprimer le message de remplissage initial
        //     recapTableBody.innerHTML = '';

        // // Vérifier qu'il y a des désignations à afficher
        // if (designations.length === 0) {
        //     recapTableBody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: red;">Aucune désignation sélectionnée</td></tr>';
        //     return;
        // }

        // // Ajouter chaque désignation dans une ligne du tableau
        // designations.forEach(designation => {
        //     const row = document.createElement('tr');

        //     row.innerHTML = `
        //         <td>${designation.designation}</td>
        //         <td>${designation.quantity}</td>
        //         <td>${designation.pu}</td>
        //         <td>${designation.total}</td>
        //         <td>
        //             <button type="button" class="btn btn-danger" onclick="removeRow(this)">Supprimer</button>
        //         </td>
        //     `;

        //     recapTableBody.appendChild(row);
        // });

        // Passer à l'étape 3
        document.getElementById('step2').style.display = 'none';
        document.getElementById('step3').style.display = 'block';
        currentStep++;
        updateStepper();
    }
}

// Fonction pour revenir à l'étape précédente
function prevStep() {
    if (currentStep > 1) {
        document.getElementById(`step${currentStep}`).style.display = 'none';
        currentStep--;
        document.getElementById(`step${currentStep}`).style.display = 'block';
        updateStepper();
    }
}

// Fonction pour réinitialiser le formulaire
function resetForm() {
    document.getElementById('wizardForm').reset();
    currentStep = 1;
    document.getElementById('step1').style.display = 'block';
    document.getElementById('step2').style.display = 'none';
    document.getElementById('step3').style.display = 'none';
    updateStepper();
}

// Fonction pour générer le PDF
function generatePDF() {
    console.log("Début de la fonction generatePDF");

    // Récupérer les valeurs du formulaire
    const clientId = document.getElementById('client-select')?.value;
    const dateEmission = document.getElementById('recap-date-emission')?.textContent.trim();
    const dateEcheance = document.getElementById('recap-date-echeance')?.textContent.trim();
    const numBc = document.getElementById('recap-numero-bc')?.textContent.trim();
    const totalHt = document.getElementById('recap-total-ht')?.textContent.trim();
    const tva = document.getElementById('recap-tva')?.textContent.trim();
    const totalTtc = document.getElementById('recap-total-ttc')?.textContent.trim();
    const accompte = document.getElementById('recap-accompte')?.textContent.trim();
    const solde = document.getElementById('recap-solde')?.textContent.trim();

    if (!clientId || !dateEmission || !dateEcheance || !totalHt || !tva || !totalTtc || !solde) {
        console.log("Valeurs des champs :");
        console.log("clientId:", clientId);
        console.log("dateEmission:", dateEmission);
        console.log("dateEcheance:", dateEcheance);
        console.log("totalHt:", totalHt);
        console.log("tva:", tva);
        console.log("totalTtc:", totalTtc);
        console.log("solde:", solde);

        alert("Veuillez remplir tous les champs obligatoires.");
        return;
    }


    const data = {
        user_id: 1, // Modifier dynamiquement avec l'utilisateur connecté
        banque_id: 1, // Modifier dynamiquement si nécessaire
        client_id: clientId,
        date_emission: dateEmission,
        date_echeance: dateEcheance,
        num_bc: numBc,
        totall_ht: totalHt,
        tva: tva,
        total_ttc: totalTtc,
        accompte: accompte,
        solde: solde,
    };

    console.log("Données envoyées :", data);

    fetch('/dashboard/devis', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify(data),
    })
    .then(response => {
        if (!response.ok) throw new Error(`Erreur serveur: ${response.status}`);
        return response.blob();
    })
    
}


// Fonction pour mettre à jour le récapitulatif avec les données du formulaire
function updateRecap() {
    // Récupérer les valeurs des champs du formulaire
    const nom = document.getElementById('client-nom').value;
    const dateEmission = document.getElementById('date-emission').value;
    const dateEcheance = document.getElementById('date-echeance').value;
    const numeroBc = document.getElementById('numero-bc').value;

    const totalHt = document.getElementById('total-ht').value;
    const tva = document.getElementById('tva').value;
    const totalTtc = document.getElementById('total-ttc').value;
    const accompte = document.getElementById('accompte').value;
    const solde = document.getElementById('solde').value;

    // Injecter les valeurs dans le récapitulatif
    document.getElementById('recap-nom').textContent = nom;
    document.getElementById('recap-date-emission').textContent = dateEmission;
    document.getElementById('recap-date-echeance').textContent = dateEcheance;
    document.getElementById('recap-numero-bc').textContent = numeroBc;

    document.getElementById('recap-total-ht').textContent = totalHt;
    document.getElementById('recap-tva').textContent = tva;
    document.getElementById('recap-total-ttc').textContent = totalTtc;
    document.getElementById('recap-accompte').textContent = accompte;
    document.getElementById('recap-solde').textContent = solde;
}

// Initialiser le stepper au chargement de la page
updateStepper();
</script>
@endpush