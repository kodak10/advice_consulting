<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Facture Proforma</title>
    <style>
        

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
            background-color: #f4f4f4;
            color: #000000;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .header {
            background-color: #f4f4f4;
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
            background-color: #f4f4f4;
            color: white;
            font-weight: bold;
        }

        .info-client {
            /* background-color: #f4f4f4; */
        }

        

       

        /* Divider en bas */
        .divider {
            border-top: 3px solid #000000;
            margin: 20px 0;
        }
        .footer{
                font-size: 9px !important;
                color: #0064c9 !important;
                text-align: center
            }

        /* Informations de l'entreprise */
        .company-info {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            border-top: 3px solid #000000;
            padding: 10px 0;
        }

        
        

        .company-info td {
            border: none;
            padding: 5px;
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
        .no-border {
            border: none;
            border-collapse: collapse; /* Facultatif : fusionner les bordures entre les cellules */
        }

        .no-border td,
        .no-border th {
            border: none; /* Assurer qu'aucune bordure n'est appliquée sur les cellules */
        }

        .no-border td:last-child{
            color: #022344;
            font-weight: bold;
            font-size: 14px;

        }
        .no-border img{
            height: 80px;
        }
        .ligne {
            height: 2px;
            width: 100%;
            background-color: #c54f00;
            margin-bottom: 20px;
        }
        .proforma{
            font-size: 21px;
            font-weight: bold;
            color: #0064c9;
        }
        .elements{
            background-color: #575656 !important;
            color: #ddd;
        }
    </style>
</head>

<body>
    
    <table class="no-border">
        <tr>
            <!-- Image avec colspan correctement défini -->
            <td colspan="5">
                <img src="{{ public_path('assets/images/logo.png') }}" alt="Logo" style="width: 100%; max-width: 200px;">
            </td>
            <!-- Texte avec colspan également correctement défini -->
            <td colspan="7">
                MONETIQUE - TECHNOLOGIE - VENTE - INGENIERIE - ETUDE
            </td>
        </tr>
    </table>
    <div class="ligne"></div>

    <table>
        <!-- Informations de la facture -->
        <tr>
            <td colspan="4" class="proforma">FACTURE PROFORMA</td>
            <td colspan="6"><strong>{{ $devis->client->nom }}</strong></td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td colspan="6"><strong>N°CC:</strong> {{ $devis->client->numero_cc }}</td>
        </tr>
        <tr>
            <td colspan="4">Date émission: {{ $devis->date_emission }}</td>
            <td colspan="6"><strong>Téléphone:</strong> {{ $devis->client->telephone }}</td>
        </tr>
        <tr>
            <td colspan="4">Numéro: {{ $devis->num_proforma }}</td>
            <td colspan="6"><strong>Adresse:</strong> {{ $devis->client->adresse }}</td>
        </tr>

        <tr>
            <td colspan="10">
                {{ $devis->texte }}            
            </td>
        </tr>

        <tr class="elements">
            <th>Référence</th>
            <th colspan="3">Description</th>
            <th>Quantité</th>
            <th colspan="1">Prix unitaire</th>
            <th colspan="1">Remise</th>
            <th colspan="3">Total</th>
        </tr>
        
        @foreach ($devis->details as $devisDetail)
            <tr>
                <td>{{ $devisDetail->designation->reference }}</td>
                <td colspan="3">{{ $devisDetail->designation->description }}</td>
                <td>{{ $devisDetail->quantite }}</td>
                <td colspan="1">{{ floor($devisDetail->prix_unitaire) }}</td>
                <td colspan="1">{{ floor($devisDetail->remise) }}</td>
                <td colspan="3">{{ floor($devisDetail->total) }}</td>
            </tr>
        @endforeach
        

        <!-- Conditions financières et Prix -->
        <tr>
            <td colspan="4" class="conditions">
                <strong>Commande :</strong> {{ $devis->commande }}% <strong>Livraison {{ $devis->livraison }} %</strong>
            </td>
            <td colspan="6" class="prices">
                <strong>Total HT :</strong> {{ floor($devis->total_ht) }}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="conditions">
                <strong>Validité de l'offre :</strong> {{ $devis->validite }} jours
            </td>
            <td colspan="6" class="prices">
                <strong>TVA :</strong> {{ $devis->tva }} %
            </td>
        </tr>
        <tr>
            <td colspan="4" class="conditions">
                <strong>Délai de livraison :</strong> {{ $devis->delai }}
            </td>
            <td colspan="6" class="prices">
                <strong>TOTAL TTC :</strong> {{ floor($devis->total_ttc) }}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="conditions">
                <strong>Veuillez libeller votre chèque au nom de :</strong>
                <strong>ADVICE CONSULTING</strong> ou faire un virement en notre faveur sur le compte ci-dessous:
            </td>
            <td colspan="6" class="prices">
                <strong>Acompte :</strong> {{ floor($devis->acompte) }}
            </td>
        </tr>
        <tr>
            <td colspan="4" class="conditions">
                <strong>Banque :</strong> {{ $banque->name }}
                <strong>N° compte :</strong> {{ $banque->num_compte }}
            </td>
            <td colspan="6" class="prices">
                <strong>Solde :</strong> {{ floor($devis->solde) }}
            </td>
        </tr>

        <!-- Signature et accord -->
        <tr>
            <td colspan="4">
                Arrêté la présence facture à la somme de 
                {{-- {{ ucwords((new NumberFormatter('fr', NumberFormatter::SPELLOUT))->format($devis->solde)) }} {{ $devis->devise }} <br> kodak--}} 
                <br>
                Veuillez confirmer votre accord par la mention "<strong>Bon pour accord</strong>"  suivi de votre signature
            </td>
            <td colspan="6" class="info-client">
                <strong>Le Service Commercial</strong><br>
                {{ $devis->user->name }}
            </td>
        </tr>
    </table>


    <!-- Informations de l'entreprise -->
    <table class="company-info" width="100%">
        <tr>
            <td class="footer">SARL au capital de 2000000 FCFA - Cocody - Angré - Villa - Adresse: 08 BP 3667 Abidjan - Tel: +225 22 54 50 53 - Fax: +225 22 54 50 53 - N°CC:0906802 G</td>
        </tr>
    </table>
</body>

</html>
