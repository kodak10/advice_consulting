<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"> <!-- Lien vers Bootstrap localement -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 200px 0px 0px 0px; /* Marge importante en haut, petite sur les côtés */
            padding: 0;
        }
        .invoice-container {
            width: 100%; /* Utiliser toute la largeur de la page */
            padding: 20px;
            margin: auto;
        }
        
        .header {
            
            justify-content: space-between;
            align-content: center;
            background-color: #b3a8a2;
            padding-bottom: 10px;
            width: 100%;
            text-align: center;
        }
       
        .info-client-container {
   
}



.info {
    width: 40%;
}

.client-container {
    width: 60%;
   
}

        .section-title {
            background: #f0f0f0;
            padding: 5px;
            font-weight: bold;
            width: 150px;
        }
        .client-info {
            flex-grow: 1;
            /* margin-left: 10px; */
            border: 1px solid #ddd;
            padding: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        .payment-footer-container {
            display: flex;
        }
        .payment {
            flex: 2; /* Partie Banque plus large */
        }
        .footer {
            flex: 1; /* Partie Totaux plus petite */
        }
        .signature {
            margin-top: 30px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td colspan="3" style="border-bottom: 1px solid #000; padding-bottom: 5px;"><strong>Type règlement facture:</strong> À échéance</td>
                <td><strong>Délai:</strong> {{ $devis->description_designation }}</td>
                <td><strong>Agent:</strong> {{ $client->nom }}</td>
            </tr>
        </table>
        
        <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 40%; border-top: 1px solid #000; padding-top: 5px;">
                    <strong>Date emission:</strong> {{ $devis->date_emmision }} <br>
                    <strong>N° Pro-forma:</strong> {{ $devis->num_proforma }} <br>
                    <strong>N° BC:</strong> <br>
                    <strong>N° Rap Activ:</strong> <br>
                    <strong>N° BL:</strong> <br>
                </td>
                <td style="width: 60%; border-top: 1px solid #000; padding-top: 5px;">
                    <strong>Client</strong><br>
                    <strong>Nom:</strong> {{ $devis->client->nom }}<br>
                    <strong>N°CC:</strong> <br>
                    <strong>Adresse:</strong> {{ $devis->client->adresse }}<br>
                    <strong>Téléphone:</strong> {{ $devis->client->telephone }}<br>
                    <strong>Ville:</strong> {{ $devis->client->ville }}
                </td>
            </tr>
        </table>
        
        <h3>Détails</h3>
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
            <tr>
                <th>Référence</th>
                <th>Description</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>{{ $devis->ref_designation }}</td>
                <td style="text-align: center; color: red;">{{ $devis->description_designation }}</td>
                <td>{{ $devis->qte_designation }}</td>
                <td>{{ $devis->prixUnitaire_designation }}</td>
                <td>{{ $devis->total_designation }}</td>
            </tr>
        </table>
        
        <table style="width: 100%; margin-top: 10px; border-collapse: collapse; border: 1px solid #000;">
            <tr>
                <!-- Informations de paiement (60%) -->
                <td style="width: 60%; vertical-align: top; padding: 10px; border-right: 1px solid #000;">
                    <strong>Informations de paiement</strong><br>
                    <strong>Banque:</strong> VERSUS BANK<br>
                    <strong>Compte:</strong> C112 01001 012206440008 24
                    
                    <p><strong>Arrêté la présente somme de :</strong> {{ ucwords((new NumberFormatter('fr', NumberFormatter::SPELLOUT))->format($devis->total_ttc)) }} francs CFA</p>

                </td>
                
                <!-- Totaux (40%) -->
                <td style="width: 40%; vertical-align: top; padding: 10px;">
                    <strong>Totaux</strong><br>
                    <strong>Total HT:</strong> {{ $devis->totall_ht }}<br>
                    <strong>TVA 18%:</strong> {{ $devis->tva }}<br>
                    <strong>Total TTC:</strong> {{ $devis->total_ttc }}<br>
                    <strong>Acompte:</strong> {{ $devis->accompte }}<br>
                    <strong>Solde:</strong> {{ $devis->solde }}
                </td>
            </tr>
        </table>
        
        
        
        <div class="signature" style="margin-top: 20px;">
            <p><strong>Cachet et signature:</strong></p>
        </div>
    </div>
    
</body>
</html>
