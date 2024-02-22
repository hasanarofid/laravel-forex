<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Ratio;
use App\Models\TransactionApi;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function index(Request $request)
{
    $filterCurrency = $request->input('filter_currency');
    $filterBroker = $request->input('filter_broker');
    $type = $request->input('type');

    $lastTransactionApi = TransactionApi::orderBy('last_update', 'desc')->first();

    // Ambil data ratio untuk pasangan mata uang
    $ratios = Ratio::where('transaction_api_id', optional($lastTransactionApi)->id)
                    ->where('type', 'Pairs');

    if ($filterCurrency) {
        $ratios->where('currency', $filterCurrency);
    } else {
        $ratios->where('currency', 'AUDJPY');
    }

    $ratios = $ratios->latest('updated_at')->get();

    // Ambil data ratio untuk broker
    $ratio_brokers = Ratio::where('transaction_api_id', optional($lastTransactionApi)->id)
                            ->where('type', 'Brokers');

    if ($filterBroker) {
        $ratio_brokers->where('currency', $filterBroker);
    } else {
        $ratio_brokers->where('currency', 'amarkets');
    }

    $ratio_brokers = $ratio_brokers->latest('updated_at')->get();

    $cur = $filterCurrency ?: 'AUDJPY';
    $brok = $filterBroker ?: 'amarkets';

    $lastUpdate = optional($lastTransactionApi)->last_update ? Carbon::parse($lastTransactionApi->last_update)->diffForHumans() : null;

    $currency = Ratio::select('currency')->where('type','Pairs')->groupBy('currency')->orderBy('currency')->get();
    $brokers = Ratio::select('currency')->where('type','Brokers')->groupBy('currency')->orderBy('currency')->get();


    return view('beranda.home', compact('currency', 'ratios', 'cur', 'lastUpdate', 'brokers', 'brok', 'ratio_brokers'));
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
