<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Wallet\Transactions;
use Illuminate\Support\Facades\Auth;
use PDF;

class DownloadController extends Controller
{
    public function export()
    {
        $id = Auth::id();

        return Excel::download(new TransactionsExport($id), 'balance_statement.xlsx');
    }

    public function exportToPDF()
    {       
        $id = Auth::id();
        $data = Transactions::where('wallet_id', $id)
                ->orderby('transact_date', 'DESC')
                // ->paginate(10);
                ->get();
                
        $pdf = PDF::loadView('transactions', ['datas' => $data]);
        // $pdf = PDF::loadView('transactions');
        return $pdf->download('transactions.pdf');
    }

    public function exportWithDateToPDF(Request $request)
    {

        $month = $request->input('month');
        $year = $request->input('year');

        $id = Auth::id();
        $data = Transactions::where('wallet_id', $id)
                ->whereMonth('transact_date', '=', $month)
                ->whereYear('transact_date', '=', $year)
                ->orderby('transact_date', 'DESC')
                ->get();

        $pdf = PDF::loadView('transactions', ['datas' => $data]);
        return $pdf->download('transactionsWithDate.pdf');
        // return $data;
    }

    public function index2()
    {
        $id = Auth::id();
        $data = Transactions::where('wallet_id', 1)
                ->orderby('transact_date', 'DESC')
                ->get();

        // return Transactions::all();
        // return view('transactions')->with('datas', $data);
        return view('transactions', ['datas' => $data]);
    }
}
