@extends('administration.layouts.master')

@section('content')
<section id="recapitulatif" class="">
    <h2>Récapitulatif de la Facture</h2>

<p><strong>Client :</strong> {{ $validatedData['client_id'] }}</p>
<p><strong>Date d'émission :</strong> {{ $validatedData['date_emission'] }}</p>
<p><strong>Date d'échéance :</strong> {{ $validatedData['date_echeance'] }}</p>
<p><strong>Numéro BC :</strong> {{ $validatedData['numero_bc'] }}</p>

<h3>Désignations :</h3>
{{-- <ul>
    @foreach ($validatedData['designations'] as $designation)
        <li>{{ $designation['description'] }} - {{ $designation['quantite'] }} x {{ $designation['pu'] }} = {{ $designation['quantite'] * $designation['pu'] }}</li>
    @endforeach
</ul> --}}

<form action="{{ route('dashboard.devis.store') }}" method="POST">
    @csrf
    <button type="submit">Confirmer et Enregistrer</button>
</form>

<a href="{{ route('dashboard.devis.create') }}">Retour</a>


</section>
@endsection
