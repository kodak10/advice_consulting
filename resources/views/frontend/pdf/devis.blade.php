<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}"> <!-- Lien vers Bootstrap localement -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px 0px 0px 0px; /* Marge importante en haut, petite sur les côtés */
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
       
        
        <table style="width: 100%; margin-top: 10px; border-collapse: collapse;">
            <tr>
                
                <td style="width: 40%; border-top: 1px solid #000; padding-top: 5px;">
                    <p>FACTURE PROFORMA</p>
                    <strong>Date emission:</strong> {{ $devis->date_emission }} <br>
                    <strong>Numero: </strong> {{ $devis->num_proforma }} <br>
                </td>
                <td style="width: 60%; border-top: 1px solid #000; padding-top: 5px;">
                    <strong>Client</strong><br>
                    <strong>Nom:</strong> {{ $devis->client->nom }}<br>
                    <strong>N°CC:</strong> {{ $devis->client->numero_cc }}<br>
                    <strong>Adresse:</strong> {{ $devis->client->adresse }}<br>
                    <strong>Téléphone:</strong> {{ $devis->client->telephone }}<br>
                    <strong>Ville:</strong> {{ $devis->client->ville }}<br>
                    <strong>ATTN:</strong> 

                </td>
            </tr>
        </table>
        
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
            <tr>
                <th>Référence</th>
                <th>Description</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Rémise</th>
                <th>Total</th>
            </tr>
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

        </table>
        
        <table style="width: 100%; margin-top: 10px; border-collapse: collapse; border: 1px solid #000;">
            <tr>
                <!-- Informations de paiement (60%) -->
                <td style="width: 60%; vertical-align: top; padding: 10px; border-right: 1px solid #000;">
                    <p>Conditions financières</p>
                    <strong>Commande:</strong> {{ $devis->commande }} <br>
                    <strong>Validité:</strong> {{ $devis->validite }} <br>
                    <strong>Délai de livraison:</strong> {{ $devis->delai }} <br>
                    <span>veuillez libeller votre chèque au nom de <strong>ADVICE CONSULTING</strong> ou faire en notre faveur sur le compte ci-dessous</span>
    
    
                    <strong>Informations de paiement</strong><br>
                    <strong>Banque:</strong> VERSUS BANK<br>
                    <strong>Compte:</strong> C112 01001 012206440008 24
                    
                    <p><strong>Arrêté la présente somme de :</strong> {{ ucwords((new NumberFormatter('fr', NumberFormatter::SPELLOUT))->format($devis->details->sum('total'))) }} francs CFA</p>
                </td>
                
                <!-- Totaux (40%) -->
                <td style="width: 40%; vertical-align: top; padding: 10px;">
                    <strong>Totaux</strong><br>
                    <strong>Total HT:</strong> {{ $devis->total_ht }}<br>
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

        <div class="signature" style="margin-top: 20px;">
            <p><strong>Service commercial</strong> <br> {{ $devis->user->name }}</p>
        </div>
    </div>
    
</body>
</html>
