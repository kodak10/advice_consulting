<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Devise;

class DeviseController extends Controller
{
    /**
     * Récupérer toutes les devises
     */
    public function index()
    {
        try {
            $devises = Devise::get();
            
            return response()->json([
                'success' => true,
                'data' => $devises
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des devises',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les taux de change - CORRIGÉ
     */
    public function getTauxChange(Request $request)
    {
        try {
            $baseDevise = $request->get('base', 'XOF');
            $apiKey = "d4a11ade825bdc9907f23c6a";
            
            $response = Http::get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/{$baseDevise}");
            
            if ($response->successful()) {
                $rates = $response->json()['conversion_rates'];
                
                // NE PAS inverser les taux - utiliser les taux directs de l'API
                // L'API retourne déjà 1 XOF = X EUR, 1 XOF = X USD, etc.
                return response()->json($rates);
                
            } else {
                // Taux par défaut plus réalistes
                $defaultRates = [
                    'XOF' => 1,
                    'EUR' => 0.0015,  // 1 XOF = 0.0015 EUR
                    'USD' => 0.0016,  // 1 XOF = 0.0016 USD
                ];
                
                return response()->json($defaultRates);
            }
            
        } catch (\Exception $e) {
            // Taux par défaut en cas d'erreur
            $defaultRates = [
                'XOF' => 1,
                'EUR' => 0.0015,
                'USD' => 0.0016,
            ];
            
            return response()->json($defaultRates);
        }
    }
}