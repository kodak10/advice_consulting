<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Facture Proforma</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            font-size: 1.2em;
            text-align: center;
        }
        .highlight {
            background-color: #ffeb3b;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .total {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        .info-client {
            background-color: #f4f4f4;
        }
        .conditions {
            background-color: #e8f5e9; /* Couleur de fond pour les conditions financières */
        }
        .prices {
            background-color: #fff3e0; /* Couleur de fond pour les prix */
        }
    </style>
</head>

<body>
    <table>
        <!-- En-tête de la facture -->
        <!-- <tr>
            <td colspan="12" class="header">FACTURE PROFORMA</td>
        </tr> -->

        <!-- Informations de la facture (ligne par ligne) -->
        <tr>
            <td colspan="6"></td>
            <td colspan="6"><strong>{{ $devis->client->nom }}</td>
        </tr>.
        <div class=></div>
        <tr>
            <td colspan="6">Facture proforma<strong></strong> [Référence de la facture]</td>
            <td colspan="6"><strong>Adresse:</strong> {{ $devis->client->adresse }}</td>
        </tr>

        <!-- Informations du client (ligne par ligne) -->
        <tr class="info-client">
            <td colspan="6">Date emmission: {{ $devis->date_emission }}</td>
            <td colspan="6"><strong>Téléphone:</strong> {{ $devis->client->telephone }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6">Numéro: {{ $devis->num_proforma }}</td>
            <td colspan="6"><strong>Ville:</strong> {{ $devis->client->ville }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6"></td>
            <td colspan="6"><strong>N°CC:</strong> {{ $devis->client->numero_cc }}</td>
        </tr>

        <!-- Message d'introduction -->
        <tr>
            <td colspan="12">
                Merci de nous consulter, veuillez trouver notre meilleure offre pour les travaux de remplacement de vos guichets.
            </td>
        </tr>

        <!-- En-tête du tableau des produits -->
        <tr>
            <th>Référence</th>
            <th>Description</th>
            <th>Quantité</th>
            <th>Prix unitaire</th>
            <th>Remise</th>
            <th>Total</th>
        </tr>

        <!-- Lignes de produits -->
        @foreach ($devis->details as $devisDetail)
                <tr>
                    <td>{{ $devisDetail->designation->reference }}</td>
                    <td>{{ $devisDetail->designation->description }}</td>
                    <td>{{ $devisDetail->quantite }}</td>
                    <td>{{ $devisDetail->prix_unitaire }}</td>
                    <td>{{ $devisDetail->remise }}</td>
                    <td>{{ $devisDetail->total }}</td>
                </tr>
        @endforeach
        <!-- Fin des lignes de produits -->

        <!-- Conditions financières (côté gauche) et Prix (côté droit) -->
        <tr>
            <td colspan="6" class="conditions">
                <strong>Commande :</strong> {{ $devis->commande }}
            </td>
            <td colspan="6" class="prices">
                <strong>Total HT :</strong> {{ $devis->total_ht }} {{ $devis->devise }}
            </td>
        </tr>
        <tr>
            <td colspan="6" class="conditions">
                <strong>Validité de l'offre :</strong> {{ $devis->validite }}
            </td>
            <td colspan="6" class="prices">
                <strong>TVA 18% :</strong> {{ $devis->tva }}
            </td>
        </tr>
        <tr>
            <td colspan="6" class="conditions">
                <strong>Délai de livraison :</strong> {{ $devis->delai }}
            </td>
            <td colspan="6" class="prices">
                <strong>TOTAL TTC :</strong> {{ $devis->total_ttc }} {{ $devis->devise }}
            </td>
        </tr>
        <tr>
            <td colspan="6" class="conditions">
                <strong>Veuillez libeller votre chèque au nom de :</strong><br>
                <strong>ADVICE CONSULTING</strong>
            </td>
            <td colspan="6" class="prices">
                <strong>Acompte :</strong> {{ $devis->accompte }} {{ $devis->devise }}
            </td>
        </tr>
        <tr>
            <td colspan="6" class="conditions">
                <strong>Banque :</strong> {{ $banque->name }}<br>
                <strong>N° compte :</strong> {{ $banque->num_compte }}
            </td>
            <td colspan="6" class="prices">
                <strong>Solde :</strong> {{ $devis->solde }} {{ $devis->devise }}
            </td>
        </tr>

        <!-- Signature et accord -->
        <tr>
            <td colspan="6">
                Arrêté la présence facture à la somme de 
                {{ ucwords((new NumberFormatter('fr', NumberFormatter::SPELLOUT))->format($devis->details->sum('total'))) }} {{ $devis->devise }}
            </td>
            <td colspan="6" class="info-client">
                <strong>Le Service Commercial</strong><br>
                {{ $devis->user->name }}
            </td>
        </tr>
    </table>
</body>

</html>