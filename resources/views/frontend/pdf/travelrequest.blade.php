<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Devis</title>
    <style>
        /* [VOTRE STYLE EXISTANT - JE NE TOUCHE À RIEN] */
        @page {
            size: A4;
            margin: 10mm 20mm 20mm 20mm; /* top right bottom left */
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-size: 15px;
            line-height: 1.2;
            text-indent: 0; /* Supprime tout retrait de première ligne */

        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-spacing: 0; /* Supprime l'espacement entre cellules */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            line-height: 0.8;
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
        .divider {
            border-top: 3px solid #000000;
            margin: 20px 0;
        }
        .footer{
            font-size: 10px;
            color: #0064c9 !important;
        }
        .company-info {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 15px;
            border-top: 3px solid #c31212;
            padding: 5px 0;
        }
        .company-info td {
            border: none;
            padding: 3px;
        }
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
            border-collapse: collapse;
        }
        .no-border td,
        .no-border th {
            border: none;
        }
        .chiffres .no-border td,
        .chiffres .no-border {
            background-color: #ffff;
            border: none;
        }
        /* .no-border td:last-child{
            color: #022344;
            font-weight: bold;
            font-size: 14px;
        } */
        .no-border img{
            height: 50px;
        }
        .ligne {
            height: 2px;
            width: 100%;
            background-color: #c54f00;
            margin-bottom: 18px;
        }
        .vide{
            height: 150px;
        }
        .proforma{
            font-size: 21px;
            font-weight: bold;
            color: #0064c9;
        }
        .no-fond {
            background-color: transparent !important;
            border-collapse: collapse;
            width: 100%;
        }
        .no-fond td{
            background-color: #ffffff !important;
        }
        #no-fond{
            background-color: #ffffff !important;
        }
        .right {
            text-align: right !important;
        }

        /* .description-cell {
            white-space: pre-wrap;
            word-wrap: break-word;
            max-width: 200px;
            padding: 5px;
        } */
         .description-cell {
            white-space: normal;  /* Remplace pre-wrap */
            word-wrap: break-word;
            max-width: 200px;
            padding: 2px 5px 2px 0 !important; /* Réduit le padding et supprime le gauche */
            margin-left: 0;
            text-indent: 0;
            text-align: left;
        }
         /* --- Header --- */
    .header {
      text-align: center;
      margin-bottom: 10px;
    }
    .company {
      font-weight: 700;
      font-size: 11px;
    }
    .company-sub {
      font-size: 9px;
      margin-top: 4px;
    }
    .hr { border-top: 1px solid #000; margin: 8px 0 12px; }

    /* --- Title --- */
    .title {
      text-align: center;
      font-size: 18px;
      font-weight: 700;
      margin: 8px 0 18px;
      letter-spacing: 1px;
    }

    /* --- Form fields --- */
    .fields { width: 100%; margin-bottom: 12px; }
    .row {
        display: flex;
        width: 100%;
        margin-bottom: 6px;
        align-items: baseline;
    }
    .row > div {
        display: flex;
        align-items: baseline;
    }

    .label {
        width: 150px;
        font-weight: 600;
        flex-shrink: 0;
    }
    .value { flex:1; border-bottom: 1px #000; padding-left:6px; }

    .full-line { width:100%; min-height: 42px; border-bottom:1px #000; padding:6px; box-sizing:border-box; }

    /* --- Table for breakdown --- */
    .table { width:100%; border-collapse: collapse; margin-top:12px; }
    .table th, .table td {
      border:1px solid #000;
      padding:6px 8px;
      text-align:left;
      font-size:12px;
    }
    .table th { background:#f2f2f2; font-weight:700; }

    /* --- Footer signature --- */
    .signatures { width:100%; display:flex; justify-content:space-between; margin-top:28px; }
    .sig-box { width:45%; text-align:center; }
    .sig-line { margin-top:40px; border-top:1px solid #000; display:inline-block; padding-top:6px; width:70%; }

    /* small print */
    .small { font-size: 20px; color:#333; margin-bottom:8px; }

    /* ensure long text wraps */
    .wrap { white-space:pre-wrap; word-wrap: break-word; }
    </style>
</head>

<body>

    <table class="no-border mt-0">
        <tr>
            <td colspan="2">
                <img src="{{ public_path('assets/images/logo.png') }}" alt="Logo" style="width: 75%; max-width: 185px;">
            </td>
            <td style="text-align: center; font-size: 13px; color: #0064c9" colspan="4">
                <strong>Monétique - Technologie - Vente - Ingénierie - Conseil - Etude</strong>
            </td>
        </tr>
    </table>

    <div class="hr"></div>

    <!-- Title -->
    <div class="title">TRAVEL REQUEST</div>

    <!-- Basic fields -->
    <div class="fields">
        <table>
            <tbody>
                <tr style="border: none">
                    <td style="border: none"><strong>Nom & Prénom : </strong>{{ $travel->nom_prenom}}</td>
                    <td style="border: none"><strong>Date : </strong>{{ $travel->date}}</td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr style="border: none">
                    <td style="border: none"><strong>Lieu : </strong>{{ $travel->lieu }}</td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr style="border: none">
                    <td style="border: none"><strong>Du : </strong>{{ $travel->debut }}</td>
                    <td style="border: none"><strong>Au : </strong>{{ $travel->fin }}</td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr style="border: none">
                    <td style="border: none"><strong><u>Motif </u>: </strong></td>
                </tr>
                <tr style="border: none">
                    <td style="background:#ffffff; border: none">{{ $travel->motif }}</td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr style="border: none">
                    <td style="border: none"><strong>Montant demandé (en chiffres) : </strong>{{ number_format($travel->montant_en_chiffre , 2, ',', ' ') }}</td>
                </tr>
            </tbody>
        </table>

        <table>
            <tbody>
                <tr style="border: none">
                    <td style="border: none"><strong>(en lettre) : </strong>{{ $travel->montant_en_lettre }}</td>
                </tr>
            </tbody>
        </table>

    <!-- Table breakdown -->
    <table class="table">
      <thead>
        <tr>
          <th style="text-align: center">Désignation</th>
          <th style="text-align: center">Montant</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Billet d’avion</td>
          <td>{{ number_format($travel->billet_avion , 2, ',', ' ') }}</td>
        </tr>
        <tr>
          <td>Chèque de voyage</td>
          <td>{{ number_format($travel->cheque , 2, ',', ' ') }}</td>
        </tr>
        <tr>
          <td>Hébergement & Repas</td>
          <td>{{ number_format($travel->hebergement_repars , 2, ',', ' ') }}</td>
        </tr>
        <tr>
          <td>Espèces</td>
          <td>{{ number_format($travel->especes , 2, ',', ' ') }}</td>
        </tr>
        <tr>
          <td style="font-weight:700">TOTAL</td>
          <td style="font-weight:700">{{  number_format($travel->totale , 2, ',', ' ') }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Signatures -->
        <table>
            <tbody>
                <tr style="border: none">
                    <td style="border: none"><strong><u>L’Intéressé : </u></strong></td>
                    <td style="border: none; text-align: center;"><strong><u>Autorisé par : </u></strong></td>
                </tr>
            </tbody>
        </table>

    <!-- Informations de l'entreprise -->
    <table class="company-info" width="100%">
        <tr>
            <td class="footer" style="text-align: center; font-size: 13px;">
                <strong>ADVICE CONSULTING. Monetique - Technologie - Vente - Ingenerie - Conseil - Etude</strong>
            </td>
        </tr>
        <tr>
            <td class="footer" style="text-align: center">
                Cocody - Angré Programme 6 villa 07 - 20 BP Abidjan - Tel. :22 50 85 29 - Fax 25 50 85 29
            </td>
        </tr>
        <tr>
            <td class="footer" style="text-align: center">
                SARL au capital de 2000000 FCFA -Email : info@advicecponsulting.net - N°RCCM : CI-ABJ-2008-B-7126
            </td>
        </tr>
        <tr>
            <td class="footer" style="text-align: center">
                Régime Fiscal : Réel Simplifié - N° CC : 0906802G
            </td>
        </tr>
    </table>
</body>
</html>
