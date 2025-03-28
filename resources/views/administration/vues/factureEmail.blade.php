<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 650px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .email-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #0056b3;
            color: white;
            padding: 25px;
            text-align: center;
        }
        .logo {
            max-width: 180px;
            margin-bottom: 15px;
        }
        .content {
            padding: 25px;
        }
        .highlight-box {
            background-color: #f8f9fa;
            border-left: 4px solid #0056b3;
            padding: 15px;
            margin: 20px 0;
        }
        .cta-button {
            display: inline-block;
            background-color: #28a745;
            color: white !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 4px;
            font-weight: bold;
            margin: 15px 0;
        }
        .signature {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f1f1f1;
            font-size: 12px;
            color: #666;
        }
        .social-links a {
            margin: 0 10px;
            text-decoration: none;
        }
        .payment-badge {
            background-color: #17a2b8;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 15px;
        }
        .facture-details {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .facture-details th, .facture-details td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .facture-details th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Advice Consulting Logo" class="logo">
            <h1>Facture n°{{ $facture->numero }}</h1>
        </div>
        
        <div class="content">
            <h2>Bonjour {{ $facture->devis->client->nom }},</h2>
            
            <p>Votre facture n° <strong>{{ $facture->numero }}</strong> a été créer par <strong>{{ $facture->user->name }}</strong>.</p>
            
            <div class="highlight-box">
                <p>📌 <strong>Détails de paiement :</strong></p>
                <div class="payment-badge">
                    À PAYER AVANT LE {{ \Carbon\Carbon::parse($facture->devis->date_echeance)->format('d/m/Y') }}
                </div>
                
                <table class="facture-details">
                    <tr>
                        <th>Montant total :</th>
                        <td>{{ number_format($facture->devis->solde, 2, ',', ' ') }} {{ $facture->devis->devise }}</td>
                    </tr>  
                    
                </table>
            </div>
            
            <p>Vous trouverez en pièce jointe le document PDF contenant le détail complet de votre facture.</p>
            
            <h3>Vos avantages :</h3>
            <ul>
                <li>✅ Paiement sécurisé en ligne disponible</li>
                <li>✅ Accès à votre espace client pour suivre vos documents</li>
                <li>✅ Service client disponible pour toute question</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="tel:+2252722545053" class="cta-button">PAYER</a>

            </div>
            
            <div class="signature">
                <p>Cordialement,</p>
                <p><strong>{{ $facture->user->name }}</strong><br>
                Advice Consulting</p>
            </div>
        </div>
        
        <div class="footer">
            <p>Cet email vous a été envoyé automatiquement, merci de ne pas y répondre directement.</p>
            
            <div class="social-links" style="margin: 15px 0;">
                <a href="https://facebook.com/adviceconsulting">Facebook</a> | 
                
                <a href="https://advice-consulting.net">Site Web</a>
            </div>
            
            <p>© {{ date('Y') }} Advice Consulting. Tous droits réservés.<br>
            </p>
            
           
        </div>
    </div>
</body>
</html>