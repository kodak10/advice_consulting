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
            background-color: #fcfcfc;
            border-color: #0056b3;
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
        .urgency-badge {
            background-color: #ffc107;
            color: #856404;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Advice Consulting Logo" class="logo">
            <h1>Proforma N°{{ $devis->num_proforma }}</h1>
        </div>
        
        <div class="content">
            <h2>Bonjour {{ $devis->client->nom }},</h2>
            
            <p>Nous avons le plaisir de vous transmettre votre proforma N° <strong>{{ $devis->num_proforma }}</strong> établie par <strong>{{ $devis->user->name }}</strong>.</p>
            
            <div class="highlight-box">
                <p>📌 <strong>Prochaine étape :</strong> Cette proforma est valable jusqu'au <strong> {{ \Carbon\Carbon::parse($devis->date_echeance)->format('d/m/Y') }}
                </strong></p>
                <div class="urgency-badge">
                    {{ $devis->date_emission }} → {{ $devis->date_echeance }} 
                    ({{ \Carbon\Carbon::parse($devis->date_emission)->diffInDays(\Carbon\Carbon::parse($devis->date_echeance)) }} jours restants)
                </div>
                
            </div>
            
            <p>Vous trouverez ci-joint le document PDF contenant le détail de votre proforma.</p>
            
            <h3>Pourquoi choisir Advice Consulting ?</h3>
            <ul>
                <li>✅ Expertise certifiée avec 10 ans d'expérience</li>
                <li>✅ Accompagnement personnalisé de A à Z</li>
                <li>✅ Solutions sur mesure adaptées à vos besoins</li>
            </ul>
            
            <div style="text-align: center;">
                <a href="tel:+2252722545053" class="cta-button">VALIDER CE PROFORMA</a>
            </div>
            
            <div class="signature">
                <p>Cordialement,</p>
                <p><strong>{{ $devis->user->name }}</strong><br>
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