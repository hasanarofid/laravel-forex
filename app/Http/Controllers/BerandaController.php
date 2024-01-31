<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Ratio;


class BerandaController extends Controller
{
    public function index()
    {
        $ratios = Ratio::where('currency','AUDJPY')->orderBy('created_at')->get();
              // Mengambil data yang diperlukan untuk chart
              $labels = [];
              $buyData = [];
              $sellData = [];
      
              foreach ($ratios as $ratio) {
                  $labels[] = $ratio->company;
                  $buyData[] = $ratio->buy;
                  $sellData[] = $ratio->sell;
              }
      
              // Mengirimkan data ke view 'beranda.home' bersama dengan compact
              return view('beranda.home', compact('labels', 'buyData', 'sellData'));
    }

    public function fetchAndSaveRatios()
    {
         // $response = Http::get('https://c.fxssi.com/api/current-ratios');
        // $data = $response->json();
        // foreach ($data['pairs'] as $currency => $pair) {
        //     foreach ($pair as $company => $value) {
        //         // Hitung nilai sell
        //         $sell = 100 - (float)$value;
                
        //         // Simpan data ke dalam tabel ratios
        //         Ratio::create([
        //             'currency' => $currency,
        //             'company' => $company,
        //             'buy' => (float)$value,
        //             'sell' => $sell,
        //         ]);
        //     }
        // }
        $response = Http::get('https://c.fxssi.com/api/current-ratios');

        if ($response->successful()) {
            $data = $response->json();
            
            foreach ($data['pairs'] as $currency => $pair) {
                foreach ($pair as $company => $value) {
                    // Hitung nilai sell
                    $sell = 100 - (float)$value;
                    
                    // Simpan data ke dalam tabel ratios
                    Ratio::create([
                        'currency' => $currency,
                        'company' => $company,
                        'buy' => (float)$value,
                        'sell' => $sell,
                    ]);
                }
            }

            return response()->json(['message' => 'Ratios fetched and saved successfully']);
        } else {
            return response()->json(['error' => 'Failed to fetch ratios from API'], 500);
        }
    }
}
