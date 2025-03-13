<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Facture</title>
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

        .vide{
            height: 150px;
        }
    </style>
</head>

<body>
    
    <div class="vide">

    </div>
    <table class="no-border">
        <tr>
            <td colspan="3">
                Type de règlement : <div class="box">A écheance</div>
            </td>
            <td colspan="3">
                Délai : <div class="box">{{ $devis->delai }}</div>
            </td>
            <!-- Texte avec colspan également correctement défini -->
            <td colspan="6">
                Agent
            </td>
        </tr>
    </table>
    <div class="ligne"></div>

    <table>
        <!-- Informations de la facture -->
        <tr>
            <td colspan="6">Date: {{ $devis->date_emission }}</td>
            <td colspan="6"><strong>Client</strong></td>
        </tr>
        <tr>
            <td colspan="6">Echéance : {{ $devis->date_echeance }}</td>
            <td colspan="6">{{ $devis->client->nom }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6">N° Pro-Forma {{ $devis->facture->numero }}</td>
            <td colspan="6"><strong>N° CC:</strong> {{ $devis->client->numero_cc }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6">N° BC: {{ $devis->facture->num_bc }}</td>
            <td colspan="6"><strong>Adresse:</strong> {{ $devis->client->adresse }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6">N° Rap Rap activ: {{ $devis->facture->num_rap }}</td>
            <td colspan="6"><strong>Téléphone</strong> {{ $devis->client->telephone }}</td>
        </tr>

        <tr>
            <td colspan="6"><strong>N° BL</strong> {{ $devis->facture->num_bl }}</td>
            <td colspan="6"><strong>Ville :</strong> {{ $devis->client->ville }}</td>
        </tr>

        <tr>
            <th colspan="1">Référence</th>
            <th colspan="4">Description</th>
            <th colspan="1">Quantité</th>
            <th colspan="2">Prix unitaire</th>
            <th colspan="4">Total</th>
        </tr>
        
        @foreach ($devis->details as $devisDetail)
            <tr>
                <td colspan="1">{{ $devisDetail->designation->reference }}</td>
                <td colspan="4">{{ $devisDetail->designation->description }}</td>
                <td colspan="1">{{ $devisDetail->quantite }}</td>
                <td colspan="2">{{ floor($devisDetail->prix_unitaire) }}</td>
                <td colspan="4">{{ floor($devisDetail->total) }}</td>
            </tr>
        @endforeach
        

        <!-- Conditions financières et Prix -->
        <tr>
            <td colspan="6" class="conditions">
                Veuillez libeller votre chèque au de Advice Consulting
            </td>
            <td colspan="6" class="prices">
                <strong>Total HT :</strong> {{ floor($devis->total_ht) }}
            </td>
        </tr>
        <tr>
            <td colspan="6" class="conditions">
                <strong> Banque :</strong> {{ $banque->name }} N° Compte {{ $banque->num_compte }}

            </td>
            <td colspan="6" class="prices">
                <strong>TVA :</strong> {{ $devis->tva }} %
            </td>
        </tr>
        <tr>
            <td colspan="6" class="conditions">
                <strong>Arrêté la présence facture à la somme de 
            </td>
            <td colspan="6" class="prices">
                <strong>TOTAL TTC :</strong> {{ floor($devis->total_ttc) }}
            </td>
        </tr>
        <tr>
            <td colspan="6" class="conditions">
                {{ ucwords((new NumberFormatter('fr', NumberFormatter::SPELLOUT))->format($devis->solde)) }} {{ $devis->devise }} <br>
            </td>
            <td colspan="6" class="prices">
                <strong>Acompte :</strong> {{ floor($devis->acompte) }}
            </td>
        </tr>
        <tr>
            <td colspan="6" class="conditions">
                
            </td>
            <td colspan="6" class="prices">
                <strong>Solde :</strong> {{ floor($devis->solde) }}
            </td>
        </tr>

        <!-- Signature et accord -->
        <tr>
            <td colspan="6">
                Cachet et signature
            </td>
            <td colspan="6" class="info-client">
               
            </td>
        </tr>
    </table>


</body>

</html>
