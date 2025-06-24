<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <title>Facture</title>
    <style>
        /* [VOTRE STYLE EXISTANT - JE NE TOUCHE À RIEN] */
        @page {
            size: A4;
            margin: 20mm;
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

        .vide{
            height: 150px;
        }
    </style>
</head>

<body>
    
   
    <div class="vide"></div>

    <table class="no-fond">
        
        <tr>
            <td colspan="6" class="no-border"></td>
            <td colspan="6"><strong>CLIENT</strong></td>
        </tr>
        <tr>
            <td colspan="6" class="no-border"><strong>Date Emission :</strong> {{ $devis->date_emission }}</td>
            <td colspan="6">{{ $devis->client->nom }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6" class="no-border"><strong>Numéro Pro-Forma :</strong> {{ $devis->facture->numero }}</td>

            <td colspan="6"><strong>N° CC :</strong> {{ $devis->client->numero_cc }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6" class="no-border"><strong>N° BC :</strong> {{ $devis->facture->num_bc }}</td>
            <td colspan="6"><strong>Téléphone :</strong> {{ $devis->client->telephone }}</td>
        </tr>
        <tr class="info-client">
            <td colspan="6" class="no-border"><strong>N° Rap Rap activ :</strong> {{ $devis->facture->num_rap }} <span></span> <strong>N° BL :</strong> {{ $devis->facture->num_bl }}</td>
            <td colspan="6"><strong>Adresse :</strong> {{ $devis->client->adresse }}</td>

        </tr>
        
        <tr>
            <td colspan="6" class="no-border"></td>
            
        </tr>
    </table>

    <table class="chiffres">
        <tr>
            <th>Ref</th>
            <th colspan="5">Description</th>
            <th colspan="1">Qté</th>
            <th colspan="3">Prix U</th>
            <th colspan="1">Rem</th>
            <th colspan="2">PU net</th>
            <th colspan="2">Montant</th>
        </tr>
        
       

        @if($facture->type_facture === 'Totale')
            <!-- Afficher tous les éléments pour une facture totale -->
            @foreach ($devis->details as $devisDetail)
                <tr>
                    <td>{{ $devisDetail->designation->reference }}</td>
                    <td colspan="5">{{ $devisDetail->designation->description }}</td>
                    <td colspan="1">{{ $devisDetail->quantite }}</td>
                    <td colspan="3">
                        @if($devis->devise === 'XOF')
                            {{ number_format($devisDetail->prix_unitaire, 0, '', ' ') }}
                        @else
                            {{ number_format($devisDetail->prix_unitaire, 2, ',', ' ') }}
                        @endif
                    </td>

                    <td colspan="1">
                        @if($devisDetail->remise > 0)
                            @if($devis->devise === 'XOF')
                                {{ number_format($devisDetail->remise, 0, '', ' ') }} %
                            @else
                                {{ number_format($devisDetail->remise, 2, ',', ' ') }} %
                            @endif
                        @endif
                    </td>
               
                    <td colspan="2">
                        @if($devis->devise === 'XOF')
                            {{ number_format($devisDetail->net_price, 0, '', ' ') }}
                        @else
                            {{ number_format($devisDetail->net_price, 2, ',', ' ') }}
                        @endif
                    </td>
                    <td colspan="2">
                        @if($devis->devise === 'XOF')
                            {{ number_format($devisDetail->total, 0, '', ' ') }}
                        @else
                            {{ number_format($devisDetail->total, 2, ',', ' ') }}
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <!-- Afficher seulement les éléments cochés pour une facture partielle -->
            @foreach ($devis->details as $devisDetail)
                @if(in_array($devisDetail->id, $selectedItems))
                    <tr>
                        <td colspan="1">{{ $devisDetail->designation->reference }}</td>
                        <td colspan="4">{{ $devisDetail->designation->description }}</td>
                        <td colspan="1">{{ $devisDetail->quantite }}</td>
                        <td colspan="3">{{ $devisDetail->prix_unitaire }}</td>
                        {{-- <td colspan="1">
                            @if($devisDetail->remise > 0)
                                @if($devis->devise === 'XOF')
                                    {{ number_format($devisDetail->remise, 0, '', ' ') }} %
                                @else
                                    {{ number_format($devisDetail->remise, 2, ',', ' ') }} %
                                @endif
                            @endif
                        </td> --}}
                        <td colspan="1" class="right">
                            {{ $devisDetail->remise }} %

                        </td>
                
                        <td colspan="2">
                            @if($devis->devise === 'XOF')
                                {{ number_format($devisDetail->net_price, 0, '', ' ') }}
                            @else
                                {{ number_format($devisDetail->net_price, 2, ',', ' ') }}
                            @endif
                        </td>
                        <td colspan="2">
                            @if($devis->devise === 'XOF')
                                {{ number_format($devisDetail->total, 0, '', ' ') }}
                            @else
                                {{ number_format($devisDetail->total, 2, ',', ' ') }}
                            @endif
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif

        @php
            if ($facture->type_facture === 'Totale') {
                $montantHT = $devis->total_ht;
                $tva = $devis->tva;
            } else {
                $montantHT = 0;
                foreach ($devis->details as $devisDetail) {
                    if (in_array($devisDetail->id, $selectedItems)) {
                        $montantHT += $devisDetail->total;
                    }
                }
            }

            $montantTVA = $montantHT * $devis->tva / 100;
            $montantTTC = $montantHT + $montantTVA;

            $acompte = $facture->type_facture === 'Totale' ? $devis->acompte : 0;
            $solde = $facture->type_facture === 'Totale' ? $devis->solde : $montantTTC;
        @endphp


        <tr>
            <td colspan="10" class="no-border" id="no-fond"></td>
            <td colspan="2" class="prices" id="no-fond">
                <strong>Total HT :</strong>
            </td>
            <td colspan="3" class="prices" id="no-fond">
                @if($devis->devise === 'XOF')
                    {{ number_format($montantHT, 0, '', ' ') }}
                @else
                    {{ number_format($montantHT, 2, ',', ' ') }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="10" class="no-border" id="no-fond"></td>
            <td colspan="2" class="prices" id="no-fond">
                <strong>TVA :</strong>
                
                @if($devis->devise === 'XOF')
                    {{ number_format($tva, 0, '', ' ') }}
                @else
                    {{ number_format($tva, 2, ',', ' ') }}
                @endif
                 %
            </td>
            <td colspan="3" class="prices" id="no-fond">
                @if($devis->devise === 'XOF')
                    {{ number_format($montantTVA, 0, '', ' ') }}
                @else
                    {{ number_format($montantTVA, 2, ',', ' ') }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="10" class="no-border" id="no-fond"></td>
            <td colspan="2" class="prices" id="no-fond">
                <strong>TOTAL TTC :</strong>
            </td>
            <td colspan="3" class="prices" id="no-fond">
                @if($devis->devise === 'XOF')
                    {{ number_format($montantTTC, 0, '', ' ') }}
                @else
                    {{ number_format($montantTTC, 2, ',', ' ') }}
                @endif
            </td>
        </tr>

        @if($facture->type_facture === 'Totale')
            <tr>
                <td colspan="10" class="no-border" id="no-fond"></td>
                <td colspan="2" class="prices" id="no-fond">
                    <strong>Acompte :</strong>
                </td>
                <td colspan="3" class="prices" id="no-fond">
                    @if($devis->devise === 'XOF')
                        {{ number_format($acompte, 0, '', ' ') }}
                    @else
                        {{ number_format($acompte, 2, ',', ' ') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td colspan="10" class="no-border" id="no-fond"></td>
                <td colspan="2" class="prices" id="no-fond">
                    <strong>Solde :</strong>
                </td>
                <td colspan="3" class="prices" id="no-fond">
                    @if($devis->devise === 'XOF')
                        {{ number_format($solde, 0, '', ' ') }}
                    @else
                        {{ number_format($solde, 2, ',', ' ') }}
                    @endif
                </td>
            </tr>
        @endif

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
           
        </tr>
       
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
            
           
        </tr>
    </table>

    <table class="no-border">
        <tr>
            <td colspan="12" id="no-fond">
                Cachet et signature
            </td>
        </tr>
    </table>
</body>
</html>