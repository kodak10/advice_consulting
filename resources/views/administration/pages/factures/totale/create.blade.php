@extends('administration.layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center mb-5">Récapitulatif de la Proforma</h3>

                    <div class="mb-5">
                        <h5>Informations du Client</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Nom :</strong> {{ $client->nom }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Adresse :</strong> {{ $client->adresse }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Téléphone :</strong> {{ $client->telephone }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5>Dates</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Date d'Émission :</strong> {{ $devis->date_emission }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Date d'Échéance :</strong> {{ $devis->date_echeance }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5>Désignations</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Remise</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($devis->details as $detail)
                                    <tr>
                                        <td>{{ $detail->designation->description }}</td>
                                        <td>{{ $detail->quantite }}</td>
                                        <td>{{ $detail->prix_unitaire }}</td>
                                        <td>{{ $detail->remise }}</td>
                                        <td>{{ $detail->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-5">
                        <h5>Conditions Financières</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Commande :</strong> {{ $devis->commande }} %</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Livraison :</strong> {{ $devis->livraison }} %</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Validité de l'offre :</strong> {{ $devis->validite }} jours</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Délai de livraison :</strong> {{ $devis->delai }} jours</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5>Banque</h5>
                        <p><strong>Nom de la banque :</strong> {{ $banque->name }}</p>
                        <p><strong>Numéro du compte:</strong> {{ $banque->num_compte }}</p>

                    </div>

                    <div class="mb-5">
                        <h5>Conditions Générales</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Total HT :</strong> {{ $devis->total_ht }} {{ $devis->devise }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>TVA :</strong> {{ $devis->tva }} %</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Total TTC :</strong> {{ $devis->total_ttc }} {{ $devis->devise }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Solde :</strong> {{ $devis->solde }} {{ $devis->devise }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <form method="POST" action="{{ route('dashboard.factures.totales.store') }}">
                @csrf
                
                <input type="hidden" name="devis_id" value="{{ $devis->id }}">
                <input type="hidden" name="banque_id" value="{{ $devis->banque->id }}">
                <input type="hidden" name="client_id" value="{{ $devis->client->id }}">
                <input type="hidden" name="type_facture" value="Totale">
                <input type="hidden" name="montant" id="montant" value="{{ old('montant', $devis->total_ttc) }}" class="form-control">
                
                


                <div class="mb-4">
                    <label class="form-label">Remise Spéciale</label>
                    <input type="number" name="remise_speciale" value="{{ $facture->remise_speciale ?? 0 }}" class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label">Numéro BC <span class="text-danger">*</span></label>
                    <input type="text" name="num_bc" placeholder="Numéro BC" value="{{ $facture->num_bc ?? '0' }}" class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label">Numéro Rap activ.</label>
                    <input type="text" name="num_rap" placeholder="Numéro RAP" value="{{ $facture->num_rap ?? '0' }}" class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label">Numéro BL</label>
                    <input type="text" name="num_bl" placeholder="Numéro BL" value="{{ $facture->num_bl ?? '0' }}" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Enregistrer</button>

                @if(Auth::user()->hasRole('Comptable'))
                    <button type="button" class="btn bg-danger-subtle text-warning px-4 fs-4 " data-bs-toggle="modal" data-bs-target="#refuseDevisModal">
                        Refuser
                    </button>
                @else
                    <button type="button" class="btn bg-danger-subtle text-warning px-4 fs-4 " data-bs-toggle="modal" data-bs-target="#refuseModal">
                        Refuser
                    </button>
                @endif

                 

                <a href="{{ route('dashboard.factures.totales.index') }}" class="btn btn-secondary">
                    Retour
                </a>
            </form>

             <!-- Modal Facture-->
             <div class="modal fade" id="refuseModal" tabindex="-1" aria-labelledby="refuseModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="refuseModalLabel">Motif du refus de la facture</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @if ($devis->facture)
                                <form method="POST" action="{{ route('dashboard.factures.totales.refuse', $devis->facture->id ) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="refuse_message" class="form-label">Message de refus</label>
                                        <textarea class="form-control" id="refuse_message" name="message" rows="4" required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-danger">Envoyer</button>
                                    </div>
                                </form>
                            @else
                                <p>Aucune facture associée à cette proforma.</p>
                            @endif
                        </div>
                    </div>
                </div>
              </div>


              <!-- Modal Proforma-->
             <div class="modal fade" id="refuseDevisModal" tabindex="-1" aria-labelledby="refuseDevisModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="refuseDevisModalLabel">Motif du refus du devis</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                                <form method="POST" action="{{ route('dashboard.devis.refuse', $devis->id ) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="refuse_message" class="form-label">Message de refus</label>
                                        <textarea class="form-control" id="refuse_message" name="message" rows="4" required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-danger">Envoyer</button>
                                    </div>
                                </form>
                            
                        </div>
                    </div>
                </div>
              </div>

                <div class="mt-4">
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

                    <!-- Affichage des erreurs de validation -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeFacture = document.getElementById('type_facture');
        const montantContainer = document.getElementById('montant_container');
        const montantInput = document.getElementById('montant');

        function toggleMontantField() {
            if (typeFacture.value === 'Partielle') {
                montantContainer.style.display = 'block';
                montantInput.value = "{{ $devis->total_ttc }}";
            } else {
                montantContainer.style.display = 'none';
                montantInput.value = "";
            }
        }

        // Événement lors du changement du type
        typeFacture.addEventListener('change', toggleMontantField);

        // Appel initial pour gérer l'affichage selon la valeur préremplie
        toggleMontantField();
    });
</script>

@endsection
