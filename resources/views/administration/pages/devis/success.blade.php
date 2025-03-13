<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proforma Approuvée</title>
</head>
<body>
    <h3>Votre Proforma a été approuvé avec succès.</h3>
    <p>Cliquez sur le bouton ci-dessous pour ouvrir votre boîte mail :</p>

    <button onclick="openGmail()">📧 Ouvrir Gmail</button>

    <script>
        function openGmail() {
            window.open("{{ $gmailUrl }}", "_blank");

            // Rediriger après ouverture de Gmail
            setTimeout(() => {
                window.location.href = "{{ route('dashboard.devis.index') }}";
            }, 2000);
        }
    </script>
</body>
</html>
