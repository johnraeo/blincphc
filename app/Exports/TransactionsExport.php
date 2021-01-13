<?php

namespace App\Exports;

use App\Wallet\Transactions;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;


class TransactionsExport implements FromCollection
{
    protected $id;

    public function  __construct($id)
    {
        $this->id= $id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // $id = Auth::id();
        $data = Transactions::where('wallet_id', $this->id)
                ->orderby('transact_date', 'DESC')
                ->paginate(10);

        // return Transactions::all();
        return $data;
    }
}
