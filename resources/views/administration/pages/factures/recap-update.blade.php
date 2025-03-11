@extends('administration.layouts.master')

@section('content')
<div class="container">
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
    <div class="row">
        <div class="col-lg-12">
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
                                <p><strong>Date d'Émission :</strong> {{ $validated['date_emission'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Date d'Échéance :</strong> {{ $validated['date_echeance'] }}</p>
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
                                @foreach ($validated['designations'] as $designation)
                                    <tr>
                                        <td>{{ $designation['designation'] }}</td>
                                        <td>{{ $designation['quantity'] }}</td>
                                        <td>{{ $designation['price'] }}</td>
                                        <td>{{ $designation['discount'] }}</td>
                                        <td>{{ $designation['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-5">
                        <h5>Conditions Financières</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Commande :</strong> {{ $validated['commande'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Livraison :</strong> {{ $validated['livraison'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Validité de l'offre :</strong> {{ $validated['validite'] }} jours</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h5>Banque</h5>
                        <p><strong>Nom de la banque :</strong> {{ $banque->name }}</p>
                    </div>

                    <div class="mb-5">
                        <h5>Conditions Générales</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <p><strong>Total HT :</strong> {{ $validated['total_ht'] }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>TVA :</strong> {{ $validated['tva'] }}%</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Total TTC :</strong> {{ $validated['total_ttc'] }}</p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Solde :</strong> {{ $validated['solde'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <form method="POST" action="{{ route('dashboard.devis.storeRecap', $devis->id) }}">                            
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                            <input type="hidden" name="date_emission" value="{{ $validated['date_emission'] }}">
                            <input type="hidden" name="date_echeance" value="{{ $validated['date_echeance'] }}">
                            <input type="hidden" name="commande" value="{{ $validated['commande'] }}">
                            <input type="hidden" name="livraison" value="{{ $validated['livraison'] }}">
                            <input type="hidden" name="validite" value="{{ $validated['validite'] }}">
                            <input type="hidden" name="banque_id" value="{{ $banque->id }}">
                            <input type="hidden" name="total_ht" value="{{ $validated['total_ht'] }}">
                            <input type="hidden" name="tva" value="{{ $validated['tva'] }}">
                            <input type="hidden" name="total_ttc" value="{{ $validated['total_ttc'] }}">
                            <input type="hidden" name="acompte" value="{{ $validated['acompte'] }}">
                            <input type="hidden" name="solde" value="{{ $validated['solde'] }}">

                            @foreach($validated['designations'] as $index => $designation)
                                <input type="hidden" name="designations[{{ $index }}][id]" value="{{ $designation['id'] }}">
                                <input type="hidden" name="designations[{{ $index }}][designation]" value="{{ $designation['designation'] }}">

                                <input type="hidden" name="designations[{{ $index }}][quantity]" value="{{ $designation['quantity'] }}">
                                <input type="hidden" name="designations[{{ $index }}][price]" value="{{ $designation['price'] }}">
                                <input type="hidden" name="designations[{{ $index }}][discount]" value="{{ $designation['discount'] }}">
                                <input type="hidden" name="designations[{{ $index }}][total]" value="{{ $designation['total'] }}">
                            @endforeach

                            <button type="submit" class="btn btn-success">Mettre à jour et Télécharger PDF</button>
                        </form>
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('dashboard.devis.index') }}" class="btn btn-primary">Retour</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
