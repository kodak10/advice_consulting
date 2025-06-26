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
            font-size: 12px;
            line-height: 1.2;
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
            font-size: 9px !important;
            color: #0064c9 !important;
        }
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
    </style>
</head>

<body>
    
    <table class="no-border mt-0">
        <tr>
            <td colspan="5">
                <img src="{{ public_path('assets/images/logo.png') }}" alt="Logo" style="width: 100%; max-width: 200px;">
            </td>
            <td colspan="7">
                MONETIQUE - TECHNOLOGIE - VENTE - INGENIERIE - ETUDE
            </td>
        </tr>
    </table>

    <div class="ligne"></div>

    <table class="no-fond">
        <tr>
            <td colspan="12" class="proforma no-border" style="text-align: center; margin-bottom:30px">FACTURE PROFORMA</td>
            
        </tr>
        <tr>
            <td colspan="6" class="no-border"></td>
            <td colspan="6"><strong>CLIENT</strong></td>
        </tr>
        <tr>
            <td colspan="6" class="no-border"></td>
            <td colspan="6">{{ $devis->client->nom }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6" class="no-border"><strong>Date Emission :</strong> {{ $devis->date_emission_fr }}</td>

            <td colspan="6"><strong>N° CC :</strong> {{ $devis->client->numero_cc }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6" class="no-border"><strong>Numéro ADC :</strong> {{ $devis->num_proforma }}</td>
            <td colspan="6"><strong>Téléphone:</strong> {{ $devis->client->telephone }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6" class="no-border"></td>
            <td colspan="6"><strong>Adresse :</strong> {{ $devis->client->adresse }}</td>
        </tr>
    </table>
    
    <table>
        <tr>
            <td colspan="10" id="no-fond">
                {{ $devis->texte }}            
            </td>
        </tr>
    </table>

    <table class="chiffres">
        <tr>
            <th colspan="1">Ref</th>
            <th colspan="4">Description</th>
            <th colspan="1">Qté</th>
            <th colspan="3">Prix U</th>
            <th colspan="1">Rem</th>
            <th colspan="2">PU net</th>
            <th colspan="2">Montant</th>
        </tr>
        
        @foreach ($devis->details as $devisDetail)
            <tr>
                <td colspan="1">{{ $devisDetail->designation->reference }}</td>
                <td colspan="4">{{ $devisDetail->designation->description }}</td>
                <td colspan="1">{{ $devisDetail->quantite }}</td>
                <td colspan="3" class="right">
                    @if($devis->devise === 'XOF')
                        {{ number_format($devisDetail->prix_unitaire, 0, '', ' ') }}
                    @else
                        {{ number_format($devisDetail->prix_unitaire, 2, ',', ' ') }}
                    @endif
                </td>
                <td colspan="1" class="right">
                    {{ $devisDetail->remise }} %

                </td>
               
                <td colspan="2" class="right">
                    @if($devis->devise === 'XOF')
                        {{ number_format($devisDetail->net_price, 0, '', ' ') }}
                    @else
                        {{ number_format($devisDetail->net_price, 2, ',', ' ') }}
                    @endif
                </td>
                <td colspan="2" class="right">
                    @if($devis->devise === 'XOF')
                        {{ number_format($devisDetail->total, 0, '', ' ') }}
                    @else
                        {{ number_format($devisDetail->total, 2, ',', ' ') }}
                    @endif
                </td>
            </tr>
        @endforeach
        

        <!-- Conditions financières et Prix -->
        <tr>
            <td colspan="6" class="no-border" id="no-fond"></td>
            <td colspan="6" class="" id="no-fond">
                <strong>Total HT :</strong> 
            </td>
            <td colspan="6" class="right" id="no-fond">
                @if($devis->devise === 'XOF')
                    {{ number_format($devis->total_ht, 0, '', ' ') }}
                @else
                    {{ number_format($devis->total_ht, 2, ',', ' ') }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="6" class="no-border" id="no-fond"></td>
            <td colspan="6" class="" id="no-fond">
                <strong>TVA :</strong> {{ $devis->tva }} %
            </td>
            <td colspan="6" class="right" id="no-fond">
                @if($devis->devise === 'XOF')
                    {{ number_format($devis->total_ht * $devis->tva / 100, 0, '', ' ') }}
                @else
                    {{ number_format($devis->total_ht * $devis->tva / 100, 2, ',', ' ') }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="6" class="no-border" id="no-fond">
                <strong>Commande :</strong> {{ $devis->commande }}% <strong>Livraison: </strong> {{ $devis->livraison }} %
            </td>
            <td colspan="6" id="no-fond">
                <strong>TOTAL TTC :</strong>
            </td>
            <td colspan="6" id="no-fond" class="right">
                {{-- Affichage du total TTC selon la devise --}}
                @if($devis->devise === 'XOF')
                    {{ number_format($devis->total_ttc, 0, '', ' ') }}
                @else
                    {{ number_format($devis->total_ttc, 2, ',', ' ') }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="6" class="no-border" id="no-fond">
                <strong>Validité de l'offre :</strong> {{ $devis->validite }} jours
            </td>
            <td colspan="6" id="no-fond">
                <strong>Acompte :</strong> 
            </td>
            <td colspan="6" id="no-fond" class="right">
                {{-- Affichage de l'acompte selon la devise --}}
                @if($devis->devise === 'XOF')
                    {{ number_format($devis->acompte, 0, '', ' ') }}
                @else
                    {{ number_format($devis->acompte, 2, ',', ' ') }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="6" class="no-border" id="no-fond">
                <strong>Délai de livraison :</strong> {{ $devis->delai }}
            </td>
            <td colspan="6" id="no-fond">
                <strong>Solde :</strong>
            </td>
            <td colspan="6" id="no-fond" class="right">
                {{-- Affichage du solde selon la devise --}}
                @if($devis->devise === 'XOF')
                    {{ number_format($devis->solde, 0, '', ' ') }}
                @else
                    {{ number_format($devis->solde, 2, ',', ' ') }}
                @endif
            </td>
        </tr>
    </table>

    <table class="no-border">
        <tr>
            <td colspan="12" class="conditions" id="no-fond">
                Veuillez libeller votre chèque à l'ordre de Advice Consulting ou faire un virement sur notre compte
            </td>
        </tr>
        <tr>
            <td colspan="12" class="conditions" id="no-fond">
                <strong> Banque :</strong> {{ $banque->name }} <strong> N° Compte: </strong> {{ $banque->num_compte }}
            </td>
        </tr>
    </table>

    <table class="no-border">
        <tr>
            <td colspan="12" class="conditions" id="no-fond">
                <strong>Arrêté la présence facture à la somme de : </strong>
            </td>
            <td class="conditions" id="no-fond">
                <strong>Service Commercial</strong>
            </td>
        </tr>
        {{-- <tr>
            @php
                $formatter = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
                $solde = number_format($devis->solde, 2, '.', '');
                [$entier, $decimales] = explode('.', $solde);
                $texteEntier = $formatter->format($entier);
                $texteDecimales = isset($decimales) && intval($decimales) > 0 ? $formatter->format($decimales) : null;
            @endphp
        
            <td colspan="12" class="conditions" id="no-fond">
                {{ ucwords($texteEntier) }}
                @if($texteDecimales)
                    virgule {{ $texteDecimales }}
                @endif
                {{ $devis->devise }}<br>
            </td>
            <td class="conditions" id="no-fond">
                {{ $devis->user->name }}
            </td>
        </tr> --}}
        <tr>
            @php
                $formatter = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
                // Format différent selon la devise
                $solde = $devis->devise === 'XOF' 
                    ? number_format($devis->total_ttc, 0, '.', '')
                    : number_format($devis->total_ttc, 2, '.', '');
                
                // Gestion des cas sans décimales
                $parts = explode('.', $solde);
                $entier = $parts[0];
                $decimales = $parts[1] ?? null; // Solution à l'erreur
                
                $texteEntier = $formatter->format($entier);
                $texteDecimales = ($decimales && intval($decimales) > 0) ? $formatter->format($decimales) : null;
            @endphp
        
            <td colspan="12" class="conditions" id="no-fond">
                {{ ucwords($texteEntier) }}
                @if($texteDecimales)
                    virgule {{ $texteDecimales }}
                @endif
                {{ $devis->devise }}<br>
            </td>
            <td class="conditions" id="no-fond">
                {{
                    collect(explode(' ', $devis->user->name))
                        ->only([0, -1]) // prend le 1er et le dernier mot
                        ->implode(' ')
                }}
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