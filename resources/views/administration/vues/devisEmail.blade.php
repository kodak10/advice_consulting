<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proforma Facturé</title>
</head>
<body>
    <h2>Bonjour {{ $clientName }},</h2>
    <p>
        Votre proforma N°: <strong>{{ $devisNumber }}</strong> a été Facturé par {{ $userName }}.
    </p>
    <p>
        Vous trouverez ci-joint le fichier PDF de votre Proforma.
    </p>
    <p>
        Si vous avez des questions, n'hésitez pas à nous contacter.
    </p>
    <p>
        Cordialement,<br>
        Advice Consulting
    </p>
</body>
</html>
