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
                <td><strong>Délai:</strong> {{ $devis->delai }}</td>
                <td><strong>Agent:</strong> {{ $devis->user->name }}</td>
            </tr>
        </table>
        
        <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 50%; border-top: 1px solid #000; padding-top: 5px;">
                    <strong>Date emission:</strong> {{ $facture->created_at }} <br>
                    <strong>N° Pro-forma:</strong> {{ $facture->numero }} <br>
                    <strong>N° BC:</strong>{{ $facture->num_bc }} <br>
                    <strong>N° Rap Activ:</strong> {{ $facture->num_rap }}<br>
                    <strong>N° BL:</strong> {{ $facture->num_bl }}<br>
                </td>
                <td style="width: 50%; border-top: 1px solid #000; padding-top: 5px;">
                    <strong>Nom:</strong> {{ $devis->client->nom }}<br>
                    <strong>N°CC:</strong> {{ $devis->client->numero_cc }}<br>
                    <strong>Adresse:</strong> {{ $devis->client->adresse }}<br>
                    <strong>Téléphone:</strong> {{ $devis->client->telephone }}<br>
                    <strong>Ville:</strong> {{ $devis->client->ville }}
                </td>
            </tr>
        </table>
        
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
            <tr>
                <th>Référence</th>
                <th>Description</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Remise</th>
                <th>Total</th>
            </tr>
        
            @foreach ($facture->devis->details as $detail)
                <tr>
                    <td>{{ $detail->designation->reference }}</td>
                    <td class="width: 30%">{{ $detail->designation->description }}</td>
                    <td>{{ $detail->quantite }}</td>
                    <td>{{ floor($detail->prix_unitaire) }}</td>
                    <td>{{ floor($detail->remise) }}</td>
                    <td>{{ floor($detail->total) }} </td>                
                </tr>
            @endforeach
        </table>
        
        
        <table style="width: 100%; margin-top: 10px; border-collapse: collapse; border: 1px solid #000;">
            <tr>
                <!-- Informations de paiement (60%) -->
                <td  style="width: 60%; vertical-align: top; padding: 10px; border-right: 1px solid #000;">
                    

                    <p>Veuillez libeller votre chèque au nom de : <strong>ADVICE CONSULTING PAYS</strong></p>
                    <strong>Banque:</strong> {{ $banque->name }}
                    <strong>Compte:</strong> {{ $banque->num_compte }}
                    
                    <p><strong>Arrêté la présente somme de :</strong> {{ ucwords((new NumberFormatter('fr', NumberFormatter::SPELLOUT))->format($devis->solde)) }} {{ $devis->devise }}</p>

                </td>
                
                <!-- Totaux (40%) -->
                <td style="width: 40%; vertical-align: top; padding: 10px;">
                    <strong>Total HT:</strong> {{ floor($devis->devis) }}<br> 
                    <strong>TVA:</strong> {{ $devis->tva }} %<br>
                    <strong>Total TTC:</strong> {{ floor($devis->total_ttc) }} <br>
                    <strong>Acompte:</strong> {{ floor($devis->acompte) }} <br>
                    <strong>Solde:</strong> {{ floor($devis->solde) }}
                </td>
            </tr>
        </table>
        
        
        
        <div class="signature" style="margin-top: 20px;">
            <p><strong>Cachet et signature:</strong></p>
        </div>
    </div>
    
</body>
</html>
