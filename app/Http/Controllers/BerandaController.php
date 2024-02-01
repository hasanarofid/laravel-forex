<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Ratio;


class BerandaController extends Controller
{
    public function index(Request $request)
    {
       
        $filter = $request->input('filter');
        // dd($filter);
        if(!empty($filter)){
            $ratios = Ratio::where('currency',$filter)->latest('updated_at')->get();
            $cur = $filter;
        }else{
            $ratios = Ratio::where('currency','AUDJPY')->latest('updated_at')->get();
            $cur = 'AUDJPY';
        }
        $lastUpdate = $ratios->isEmpty() ? null : $ratios->first()->updated_at->diffForHumans();

        $currency = Ratio::select('currency')->groupBy('currency')->orderBy('currency')->get();
     
              // Mengirimkan data ke view 'beranda.home' bersama dengan compact
              return view('beranda.home', compact('currency','ratios','cur','lastUpdate'));
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
