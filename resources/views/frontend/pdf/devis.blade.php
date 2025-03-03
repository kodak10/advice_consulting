<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Facture Proforma</title>
    <style>
        /* Définir les marges et le format A4 */
        @page {
            size: A4;
            margin: 20mm; /* Marges autour du contenu */
        }

        /* Mise en page de base */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-size: 12px;
            line-height: 1.5;
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
            font-weight: bold;
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

        /* Ajustement pour l'impression */
        @media print {
            body {
                font-size: 12px;
                margin: 0;
                padding: 0;
            }

            table {
                margin-left: auto;
                margin-right: auto;
                width: 100%;
            }

            .header {
                font-size: 1.5em;
            }

            th, td {
                padding: 5px;
                font-size: 10px;
            }

            .center {
                text-align: center;
            }

            .right {
                text-align: right;
            }

            .total {
                font-weight: bold;
            }

            .info-client {
                background-color: #f4f4f4;
            }
        }
    </style>
</head>

<body>
    <table>
        <!-- Informations de la facture -->
        <tr>
            <td colspan="6"></td>
            <td colspan="6"><strong>{{ $devis->client->nom }}</strong></td>
        </tr>
        <tr>
            <td colspan="6">Facture proforma [Référence de la facture]</td>
            <td colspan="6"><strong>Adresse:</strong> {{ $devis->client->adresse }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6">Date émission: {{ $devis->date_emission }}</td>
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
                <td>{{ floor($devisDetail->prix_unitaire) }}</td>
                <td>{{ floor($devisDetail->remise) }}</td>
                <td>{{ floor($devisDetail->total) }} </td>
            </tr>
        @endforeach

        <!-- Conditions financières et Prix -->
        <tr>
            <td colspan="6" class="conditions">
                <strong>Commande :</strong> {{ $devis->commande }}
            </td>
            <td colspan="6" class="prices">
                <strong>Total HT :</strong> {{ floor($devis->total_ht) }}
            </td>
        </tr>
        <tr>
            <td colspan="6" class="conditions">
                <strong>Validité de l'offre :</strong> {{ $devis->validite }}
            </td>
            <td colspan="6" class="prices">
                <strong>TVA :</strong> {{ $devis->tva }} %
            </td>
        </tr>
        <tr>
            <td colspan="6" class="conditions">
                <strong>Délai de livraison :</strong> {{ $devis->delai }}
            </td>
            <td colspan="6" class="prices">
                <strong>TOTAL TTC :</strong>{{ floor($devis->total_ttc) }}
            </td>
        </tr>
        <tr>
            <td colspan="6" class="conditions">
                <strong>Veuillez libeller votre chèque au nom de :</strong><br>
                <strong>ADVICE CONSULTING</strong>
            </td>
            <td colspan="6" class="prices">
                <strong>Acompte :</strong> {{ floor($devis->acompte) }}
            </td>
        </tr>
        <tr>
            <td colspan="6" class="conditions">
                <strong>Banque :</strong> {{ $banque->name }}
                <strong>N° compte :</strong> {{ $banque->num_compte }}
            </td>
            <td colspan="6" class="prices">
                <strong>Solde :</strong> {{ floor($devis->solde) }}
            </td>
        </tr>

        <!-- Signature et accord -->
        <tr>
            <td colspan="6">
                Arrêté la présence facture à la somme de 
                {{ ucwords((new NumberFormatter('fr', NumberFormatter::SPELLOUT))->format($devis->solde)) }} {{ $devis->devise }}
            </td>
            <td colspan="6" class="info-client">
                <strong>Le Service Commercial</strong><br>
                {{ $devis->user->name }}
            </td>
        </tr>
    </table>
</body>

</html>
