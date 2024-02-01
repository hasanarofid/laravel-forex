<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Ratio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.index');
    }

    public function getDatatable(Request $request)
    {
        if ($request->ajax()) {
            $data = Ratio::orderBy('id', 'DESC')->get();
    
            return Datatables::of($data)
                ->addColumn('updated_at', function ($row) {
                    return Carbon::parse($row->updated_at)->format('d-M-Y H:i:s');
                })
                ->make(true);
        }
    }

    public function fetchAndSaveRatios()
        {
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

                return redirect()->back()->with('status', 'Ratios fetched and saved successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to fetch ratios from API');
            }
        }

}
