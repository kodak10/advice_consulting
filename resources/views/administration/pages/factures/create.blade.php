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
            <form method="POST" action="{{ route('dashboard.factures.store') }}">
                @csrf
                
                <input type="hidden" name="devis_id" value="{{ $devis->id }}">
                <input type="hidden" name="banque_id" value="{{ $devis->banque->id }}">
                <input type="hidden" name="client_id" value="{{ $devis->client->id }}">

                <div class="mb-4">
                    <label class="form-label">Remise Spéciale <span class="text-danger">*</span></label>
                    <input type="number" name="remise_speciale" value="0" class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label">Numéro BC <span class="text-danger">*</span></label>
                    <input type="text" name="num_bc" placeholder="Numéro BC" class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label">Numéro Rap activ. <span class="text-danger">*</span></label>
                    <input type="text" name="num_rap" placeholder="Numéro RAP" class="form-control">
                </div>

                <div class="mb-4">
                    <label class="form-label">Numéro BL <span class="text-danger">*</span></label>
                    <input type="text" name="num_bl" placeholder="Numéro BL" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Enregistrer</button>

                <a href="{{ route('dashboard.factures.refuse', $devis->id) }}" class="btn btn-danger">
                    Refuser la facture
                </a>

                <a href="{{ route('dashboard.factures.index') }}" class="btn btn-secondary">
                    Retour
                </a>
            </form>

            <div>
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
        </div>
    </div>
</div>
@endsection
