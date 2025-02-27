<?php

namespace App\Http\Controllers\Administration;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use App\Models\Client;
use App\Models\Designation;
use App\Models\Devis;
use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FacturesController extends Controller
{
   

    public function __construct()
    {
        // Bloquer l'accès aux méthodes sauf 'create', 'refuse', 'store' pour Daf
        // MAIS laisser le Comptable accéder à tout
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->hasRole('Comptable')) {
                return $next($request); // Comptable a accès à tout
            }

            // Bloquer toutes les méthodes sauf certaines pour Daf
            $this->middleware('role:Daf')->except(['index', 'refuse']);

            return $next($request);
        });
    }

    
    public function index()
    {
        $all_devis = Devis::where('status',  'Approuvé')
        ->get();

        $devis_pays = Devis::where('pays_id', Auth::user()->pays_id)
        ->where('status',  'Approuvé')
        ->get();

        $all_factures = Facture::get();

        $factures_pays = Facture::where('pays_id', Auth::user()->pays_id)
        ->get();

        $mes_factures = Facture::where('pays_id', Auth::user()->pays_id)
        ->where('user_id', Auth::user()->id)
        ->get();
        
        return view('administration.pages.factures.index', compact('all_devis', 'devis_pays', 'all_factures', 'factures_pays', 'mes_factures'));

    } 

    public function refuse($id)
    {
        $devis = Devis::findOrFail($id);

        if ($devis->status !== 'Approuvé') {
            return redirect()->back()->with('error', 'Vous ne pouvez supprimer cete Proforma que si son statut est "Approuvé".');
        }

        $devis->status = 'Réfusé';
        $devis->save();

        return redirect()->back()->with('success', 'Proforma Réfusée avec succès.');
    }

    public function create($id)
    {
        $devis = Devis::with('client', 'banque', 'details.designation')->findOrFail($id);

        if ($devis->status === 'Terminé') {
            return redirect()->back()->with('error', "Cette proforma a déjà fait l'objet d'une facture.");
        }
        
        if ($devis->status === 'Réfusé') {
            return redirect()->back()->with('error', "Cette proforma a déjà été refusée.");
        }
        
        $client = $devis->client;
        $banque = $devis->banque;
        $designations = $devis->details; // Dépend de ta relation avec DevisDetail
        
        return view('administration.pages.factures.create', compact('client', 'banque', 'designations', 'devis'));
    }

    public function generateCustomNumber()
    {
        $user = Auth::user(); // Récupérer l'utilisateur connecté
        $month = date('m'); // Mois
        $year = date('y'); // Année (2 chiffres)
        $day = date('d/m/Y'); // Date complète
        $initials = strtoupper(substr($user->name, 0, 2)); // Initiales

        // Récupérer le dernier numéro généré aujourd'hui
        $lastFacture = Facture::whereDate('created_at', today())->latest()->first();
        $counter = $lastFacture ? (intval(substr($lastFacture->numero, 5, 3)) + 1) : 1;
        $counter = str_pad($counter, 3, '0', STR_PAD_LEFT); // Format 3 chiffres

        return "{$month}{$year}-{$counter}{$initials} du {$day}";
    }

  
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'devis_id' => 'required|exists:devis,id',
                'banque_id' => 'required|exists:banques,id',
                'client_id' => 'required|exists:clients,id',
                'num_bc' => 'required|string',
                'num_rap' => 'required|string',
                'num_bl' => 'required|string',
                'remise_speciale' => 'required|string',
            ]);

            $devis = Devis::findOrFail($validated['devis_id']);
            $client = Client::findOrFail($validated['client_id']);
            $banque = Banque::findOrFail($validated['banque_id']);

            $devis->status = 'Terminé';
            $devis->save();

            $customNumber = $this->generateCustomNumber();

            $facture = new Facture();
            $facture->devis_id = $validated['devis_id'];
            $facture->num_bc = $validated['num_bc'];
            $facture->num_rap = $validated['num_rap'];
            $facture->num_bl = $validated['num_bl'];
            $facture->user_id = Auth::id();
            $facture->remise_speciale = $validated['remise_speciale'];
            $facture->numero = $customNumber;
            $facture->pays_id = Auth::user()->pays_id;

            $facture->save();

            // Génération du PDF
            $pdf = PDF::loadView('frontend.pdf.facture', compact('devis', 'client', 'banque', 'facture'));
            $pdfOutput = $pdf->output();

            $imageName = 'facture-' . $facture->id . '.pdf';
            $directory = 'pdf/factures';

            // Vérifier et créer le dossier si nécessaire
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            $imagePath = $directory . '/' . $imageName;
            Storage::disk('public')->put($imagePath, $pdfOutput);

            $facture->pdf_path = $imagePath;
            $facture->save();

            // Télécharger le fichier PDF
            return response()->download(storage_path('app/public/' . $imagePath));

            // return redirect()->route('dashboard.factures.index')->with('success', 'Facture enregistrée avec succès.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation échouée', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement de la facture', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la facture.')->withInput();
        }
    }

    public function download($id)
    {
        $factures = Facture::findOrFail($id);

        if (!$factures->pdf_path || !Storage::disk('public')->exists($factures->pdf_path)) {
            return back()->with('error', 'Le fichier demandé n\'existe pas.');
        }

        return response()->download(storage_path('app/public/' . $factures->pdf_path));
    }


//     public function exportCsv()
// {
//     $fileName = 'factures_export.csv';
//     $factures = Facture::with(['devis.client', 'user', 'devis.details'])->get();

//     $headers = [
//         "Content-type" => "text/csv",
//         "Content-Disposition" => "attachment; filename=$fileName",
//         "Pragma" => "no-cache",
//         "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
//         "Expires" => "0"
//     ];

//     return response()->stream(function () use ($factures) {
//         $handle = fopen('php://output', 'w');
        
//         if ($handle === false) {
//             throw new \Exception('Impossible d\'ouvrir php://output pour l\'écriture');
//         }

//         // Entête du fichier CSV
//         fputcsv($handle, ['Date', 'Client', 'Coût', 'Etabli Par', 'Statut']);

//         // Ajout des données
//         foreach ($factures as $facture) {
//             fputcsv($handle, [
//                 $facture->created_at,
//                 $facture->devis->client->nom,
//                 $facture->devis->details->sum('total') . ' ' . $facture->devis->devise,
//                 $facture->user->name,
//                 $facture->devis->status ?? 'Non renseigné'
//             ]);
//         }

//         fclose($handle);
//     }, 200, $headers);
// }
    public function exportCsv()
    {
        // Créer une nouvelle instance de Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Données à exporter
    $factures = Facture::with(['devis.client', 'user', 'devis.details'])->get();

    // Titre du document
    $sheet->setCellValue('A1', 'Export des Factures');
    $sheet->mergeCells('A1:F1'); // Fusionner les cellules pour le titre
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // Date d'export
    $sheet->setCellValue('A2', 'Date d\'export : ' . date('d/m/Y H:i:s'));
    $sheet->mergeCells('A2:F2');
    $sheet->getStyle('A2')->getFont()->setItalic(true);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    // En-têtes des colonnes
    $sheet->setCellValue('A4', 'Date de Création');
    $sheet->setCellValue('B4', 'Client');
    $sheet->setCellValue('C4', 'Coût Total');
    $sheet->setCellValue('D4', 'Devise');
    $sheet->setCellValue('E4', 'Établi Par');
    $sheet->setCellValue('F4', 'Statut');

    // Style des en-têtes
    $headerStyle = [
        'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']], // Fond bleu
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]], // Bordures fines
    ];
    $sheet->getStyle('A4:F4')->applyFromArray($headerStyle);

    // Remplir les données
    $row = 5;
    $totalCost = 0;
    foreach ($factures as $facture) {
        $clientName = $facture->devis->client->nom ?? 'Client inconnu';
        $userName = $facture->user->name ?? 'Utilisateur inconnu';
        $cost = $facture->devis->details->sum('total');
        $totalCost += $cost;
        $devise = $facture->devis->devise ?? 'USD';

        $sheet->setCellValue('A' . $row, $facture->created_at->format('d/m/Y H:i:s'));
        $sheet->setCellValue('B' . $row, $clientName);
        $sheet->setCellValue('C' . $row, number_format($cost, 2, ',', ' '));
        $sheet->setCellValue('D' . $row, $devise);
        $sheet->setCellValue('E' . $row, $userName);
        $sheet->setCellValue('F' . $row, $facture->devis->status ?? 'Non renseigné');

        // Style des lignes de données
        $sheet->getStyle('A' . $row . ':F' . $row)->getBorders()
            ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $row++;
    }

    // Ligne du total
    $sheet->setCellValue('B' . $row, 'Total');
    $sheet->setCellValue('C' . $row, number_format($totalCost, 2, ',', ' '));
    $sheet->getStyle('B' . $row . ':C' . $row)->getFont()->setBold(true);
    $sheet->getStyle('B' . $row . ':C' . $row)->getBorders()
        ->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    // Ajuster la largeur des colonnes
    foreach (range('A', 'F') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    // Enregistrer le fichier
    $writer = new Xlsx($spreadsheet);
    $fileName = 'factures_export_' . date('Y-m-d_H-i-s') . '.xlsx';
    $tempFile = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($tempFile);

    // Télécharger le fichier
    return response()->download($tempFile, $fileName, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ])->deleteFileAfterSend(true);
    }

    


    

}
