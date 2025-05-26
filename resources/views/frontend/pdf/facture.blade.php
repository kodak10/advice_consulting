<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Facture</title>
    <style>
        /* Votre CSS existant... */
    </style>
</head>

<body>
    
    <div class="vide"></div>

    <table class="no-fond">
        <!-- Informations de la facture -->
        <tr>
            <td colspan="6"><strong>Date :</strong> {{ $devis->date_emission }}</td>
            <td colspan="6"><strong>CLIENT</strong></td>
        </tr>
        <tr>
            <td colspan="6"><strong>Echéance :</strong> {{ $devis->date_echeance }}</td>
            <td colspan="6">{{ $devis->client->nom }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6"><strong>N° Facture :</strong> {{ $facture->numero }}</td>
            <td colspan="6"><strong>N° CC :</strong> {{ $devis->client->numero_cc }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6"><strong>N° BC :</strong> {{ $facture->num_bc }}</td>
            <td colspan="6"><strong>Adresse:</strong> {{ $devis->client->adresse }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6"><strong>N° Rap activ :</strong> {{ $facture->num_rap }}</td>
            <td colspan="6"><strong>Téléphone :</strong> {{ $devis->client->telephone }}</td>
        </tr>
        <tr>
            <td colspan="6"><strong>N° BL :</strong> {{ $facture->num_bl }}</td>
            <td colspan="6"><strong>Ville :</strong> {{ $devis->client->ville }}</td>
        </tr>
        @if($facture->type_facture === 'Partielle')
        <tr>
            <td colspan="6"><strong>Type Facture :</strong> Partielle</td>
            <td colspan="6"><strong>Montant Facture :</strong> {{ number_format($facture->montant, 2, ',', ' ') }} FCFA</td>
        </tr>
        @endif
    </table>

    <table class="chiffres">
        <tr>
            <th colspan="1">Référence</th>
            <th colspan="3">Description</th>
            <th colspan="1">Qté</th>
            <th colspan="1">Prix unitaire</th>
            <th colspan="2">Total</th>
        </tr>
        
        @foreach ($devis->details as $devisDetail)
            <tr>
                <td colspan="1">{{ $devisDetail->designation->reference }}</td>
                <td colspan="3">{{ $devisDetail->designation->description }}</td>
                <td colspan="1">{{ $devisDetail->quantite }}</td>
                <td colspan="1">{{ $devisDetail->prix_unitaire }}</td>
                <td colspan="2">{{ $devisDetail->total }}</td>
            </tr>
        @endforeach
        

        <!-- Conditions financières et Prix -->
        <tr>
            <td colspan="4" class="no-border" id="no-fond"></td>
            <td colspan="2" class="prices" id="no-fond">
                <strong>Total HT :</strong>
            </td>
            <td colspan="3" class="prices" id="no-fond">
                {{ $devis->total_ht }}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="no-border" id="no-fond"></td>
            <td colspan="2" class="prices" id="no-fond">
                <strong>TVA :</strong> {{ $devis->tva }} %
            </td>
            <td colspan="3" class="prices" id="no-fond">
                {{ number_format($devis->total_ht * $devis->tva / 100, 2, ',', ' ') }}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="no-border" id="no-fond"></td>
            <td colspan="2" class="prices" id="no-fond">
                <strong>TOTAL TTC :</strong>
            </td>
            <td colspan="3" class="prices" id="no-fond">
                {{ $devis->total_ttc }}
            </td>
        </tr>
        
        @if($facture->type_facture === 'Totale')
        <!-- Afficher acompte et solde seulement pour les factures totales -->
        <tr>
            <td colspan="4" class="no-border" id="no-fond"></td>
            <td colspan="2" class="prices" id="no-fond">
                <strong>Acompte :</strong> 
            </td>
            <td colspan="3" class="prices" id="no-fond">
                {{ $devis->acompte }}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="no-border" id="no-fond"></td>
            <td colspan="2" class="prices" id="no-fond">
                <strong>Solde :</strong> 
            </td>
            <td colspan="3" class="prices" id="no-fond">
                {{ $devis->solde }}
            </td>
        </tr>
        @else
        <!-- Pour les factures partielles, afficher le montant de la facture partielle -->
        <tr>
            <td colspan="4" class="no-border" id="no-fond"></td>
            <td colspan="2" class="prices" id="no-fond">
                <strong>Montant Facture Partielle :</strong> 
            </td>
            <td colspan="3" class="prices" id="no-fond">
                {{ number_format($facture->montant, 2, ',', ' ') }} FCFA
            </td>
        </tr>
        @endif
    </table>

    <table>
        <tr>
            <td colspan="12" class="conditions" id="no-fond">
                Veuillez libeller votre chèque à l'ordre de Advice Consulting {{ $devis->user->pays->name }} ou faire un virement sur notre compte
            </td>
        </tr>
        <tr>
            <td colspan="12" class="conditions" id="no-fond">
                <strong> Banque :</strong> {{ $banque->name }} N° Compte {{ $banque->num_compte }}
            </td>
        </tr>
        <tr>
            <td colspan="12" class="conditions" id="no-fond">
                <strong>Arrêté la présente facture à la somme de :</strong>
            </td>
        </tr>
        
        {{-- @php
            $formatter = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
            // Utiliser le montant de la facture partielle si c'est une facture partielle
            $montant = $facture->type_facture === 'Partielle' ? $facture->montant : $devis->solde;
            $montantFormat = number_format($montant, 2, '.', '');
            [$entier, $decimales] = explode('.', $montantFormat);
        
            $texteEntier = $formatter->format($entier);
            $texteDecimales = intval($decimales) > 0 ? $formatter->format($decimales) : null;
        @endphp --}}
        
        {{-- <tr>
            <td colspan="12" class="conditions" id="no-fond">
                {{ ucwords($texteEntier) }}
                @if($texteDecimales)
                    virgule {{ $texteDecimales }}
                @endif
                {{ $devis->devise }}<br>
            </td>
        </tr> --}}
    </table>

    <table class="no-border">
        <tr>
            <td colspan="12" id="no-fond">
                Cachet et signature
            </td>
        </tr>
    </table>

</body>
</html>