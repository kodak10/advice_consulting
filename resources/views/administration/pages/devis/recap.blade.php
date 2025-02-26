@extends('administration.layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center mb-5">Récapitulatif de la Proforma</h3>

                    <!-- Informations du Client -->
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

                    <!-- Dates -->
                    <div class="mb-5">
                        <h5>Dates</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Date d'Émission :</strong> {{ $validated['date_emission'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Date d'Échéance :</strong> {{ $validated['date_echeance'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Désignations -->
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
                                @foreach ($validated['designations'] as $designation)
                                    <tr>
                                        <td>{{ $designation['description'] }}</td>
                                        <td>{{ $designation['quantity'] }}</td>
                                        <td>{{ $designation['price'] }}</td>
                                        <td>{{ $designation['discount'] }}</td>
                                        <td>{{ $designation['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Conditions Financières -->
                    <div class="mb-5">
                        <h5>Conditions Financières</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Commande :</strong> {{ $validated['commande'] }}%</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Livraison :</strong> {{ $validated['livraison'] }}%</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Validité de l'offre :</strong> {{ $validated['validite'] }} jours</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Délai de livraison :</strong> {{ $validated['delai'] }} jours</p>
                            </div>
                        </div>
                    </div>

                    <!-- Banque -->
                    <div class="mb-5">
                        <h5>Banque</h5>
                        <p><strong>Nom de la banque :</strong> {{ $banque->name }}</p>
                        <p><strong>Numéro du compte :</strong> {{ $banque->num_compte }}</p>

                    </div>

                    <!-- Conditions Générales -->
                    <div class="mb-5">
                        <h5>Conditions Générales</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Total HT :</strong> {{ $validated['total-ht'] }} {{ $validated['devise'] }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>TVA :</strong> {{ $validated['tva'] }}%</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Total TTC :</strong> {{ $validated['total-ttc'] }} {{ $validated['devise'] }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Acompte :</strong> {{ $validated['acompte'] }} {{ $validated['devise'] }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Solde :</strong> {{ $validated['solde'] }} {{ $validated['devise'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton d'enregistrement -->
                    <div class="form-actions">
                        <form  method="POST" action="{{ route('dashboard.devis.store') }}" >
                            @csrf
                            
                            <input type="hidden" name="devise" value="{{ $validated['devise'] }}">

                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <input type="hidden" name="banque_id" value="{{ $banque->id }}">

                            <input type="hidden" name="date_emission" value="{{ $validated['date_emission'] }}">
                            <input type="hidden" name="date_echeance" value="{{ $validated['date_echeance'] }}">
                            
                            <input type="hidden" name="commande" value="{{ $validated['commande'] }}">
                            <input type="hidden" name="livraison" value="{{ $validated['livraison'] }}">
                            <input type="hidden" name="validite" value="{{ $validated['validite'] }}">
                            <input type="hidden" name="delai" value="{{ $validated['delai'] }}">


                            <input type="hidden" name="total-ht" value="{{ $validated['total-ht'] }}">
                            <input type="hidden" name="tva" value="{{ $validated['tva'] }}">
                            <input type="hidden" name="total-ttc" value="{{ $validated['total-ttc'] }}">
                            <input type="hidden" name="acompte" value="{{ $validated['acompte'] }}">
                            <input type="hidden" name="solde" value="{{ $validated['solde'] }}">

                            @foreach($validated['designations'] as $index => $designation)
                                <input type="hidden" name="designations[{{ $index }}][id]" value="{{ $designation['id'] }}">
                                <input type="hidden" name="designations[{{ $index }}][description]" value="{{ $designation['description'] }}">

                                <input type="hidden" name="designations[{{ $index }}][quantity]" value="{{ $designation['quantity'] }}">
                                <input type="hidden" name="designations[{{ $index }}][price]" value="{{ $designation['price'] }}">
                                <input type="hidden" name="designations[{{ $index }}][discount]" value="{{ $designation['discount'] }}">
                                <input type="hidden" name="designations[{{ $index }}][total]" value="{{ $designation['total'] }}">
                            @endforeach

                            <button type="submit" class="btn btn-success mr-3">Enregistrer et Télécharger PDF</button>
                            <a href="{{ route('dashboard.devis.index') }}" class="btn btn-primary">Retour</a>

                        </form>
                    </div>

                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
