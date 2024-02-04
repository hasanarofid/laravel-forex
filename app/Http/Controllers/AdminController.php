<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Ratio;
use App\Models\TransactionApi;
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
            $query = Ratio::select('ratios.*')
                // ->join('transaction_api', 'transaction_api.id', '=', 'ratios.transaction_api_id')
                ->orderBy('updated_at', 'DESC');
                if ($request->has('date_range')) {
                    // dd($request->input('date_range'));die;
                    // Split the date_range into start and end date
                    $dateRange = explode(' - ', $request->input('date_range'));
                    $startDate = Carbon::parse($dateRange[0])->startOfDay();
                    $endDate = Carbon::parse($dateRange[1])->endOfDay();
        
                    // Add where clause to filter data within the date range
                    $query->whereBetween('updated_at', [$startDate, $endDate]);
                }

    
            return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('last_update22', function ($row) {
                    return Carbon::parse($row->updated_at)->format('d-M-Y H:i:s');
                })
                ->addColumn('action', function($row){
                    $deleteButton = "<button class='btn btn-sm btn-danger deleteUser' data-id='".$row->id."'><i class='fa fa-solid fa-trash'></i></button>";
                    return $deleteButton;
                })
                ->make(true);
        }
    }

    public function fetchAndSaveRatios()
        {
            $response = Http::get('https://c.fxssi.com/api/current-ratios');

            // $data = $response->json();
            // dd($data);

            $currentDate = Carbon::now()->format('Ymd');
            $countTransactions = TransactionApi::whereDate('last_update', Carbon::today())->count();
            $sequenceNumber = str_pad($countTransactions + 1, 4, '0', STR_PAD_LEFT);
            $generateNo = 'API' . $currentDate . $sequenceNumber;
            $transactionApi = new TransactionApi();
            $transactionApi->generate_no = $generateNo;
            $transactionApi->last_update = Carbon::now();
            $transactionApi->save();

            if ($response->successful()) {
                $data = $response->json();
                $transactionApiId = $transactionApi->id;
                



                // save pairs
                foreach ($data['pairs'] as $currency => $pair) {
                    foreach ($pair as $company => $value) {
                        // Hitung nilai sell
                        
                        if ($value > 100.00) {
                            // Ambil dua digit pertama sebelum titik (.)
                            $value = substr($value, 0, 2) . substr($value, strpos($value, '.'));
                        }
                
                        // Hitung nilai sell
                        $sell = 100 - (float)$value;
                        
                        // Simpan data ke dalam tabel ratios
                        Ratio::create([
                            'currency' => $currency,
                            'type' => 'Pairs',
                            'transaction_api_id' => $transactionApiId,
                            'company' => $company,
                            'buy' => (float)$value,
                            'sell' => $sell,
                        ]);
                    }
                }

                // save brokers
                foreach ($data['brokers'] as $currency_brokers => $brokers) {
                    foreach ($brokers as $company_brokers => $value_brokers) {
                        // Hitung nilai sell
                        
                        if ($value_brokers > 100.00) {
                            // Ambil dua digit pertama sebelum titik (.)
                            $value_brokers = substr($value_brokers, 0, 2) . substr($value_brokers, strpos($value_brokers, '.'));
                        }
                
                        // Hitung nilai sell
                        $sell_brokers = 100 - (float)$value_brokers;
                        
                        // Simpan data ke dalam tabel ratios
                        Ratio::create([
                            'currency' => $currency_brokers,
                            'type' => 'Brokers',
                            'transaction_api_id' => $transactionApiId,
                            'company' => $company_brokers,
                            'buy' => (float)$value_brokers,
                            'sell' => $sell_brokers,
                        ]);
                    }
                }

                 
                return redirect()->back()->with('status', 'Ratios fetched and saved successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to fetch ratios from API');
            }
        }

        // delete one row
        function deleteData(Request $request){
                 $id = $request->id;
                //  dd($id);
                // Lakukan penghapusan data di sini
                // Misalnya, jika menggunakan Eloquent, Anda bisa melakukan seperti ini:
                Ratio::destroy($id);
                // Anda juga dapat menambahkan logika lainnya di sini
                // Misalnya, mengirimkan pesan ke klien tentang keberhasilan atau kegagalan penghapusan
                return response()->json(['success' => 1]);
            
        }

        // delete all
        function deleteSelected(Request $request){
            $ids = $request->ids;
            // dd($ids);
            // Lakukan penghapusan data dengan beberapa ID di sini
            // Misalnya, jika menggunakan Eloquent, Anda bisa melakukan seperti ini:
            Ratio::whereIn('id', $ids)->delete();

        //    $cek =  TransactionApi::where('transaction_api_id', $ids)->get();
            // Anda juga dapat menambahkan logika lainnya di sini
            // Misalnya, mengirimkan pesan ke klien tentang keberhasilan atau kegagalan penghapusan
            return response()->json(['success' => 1]);
        }



}


