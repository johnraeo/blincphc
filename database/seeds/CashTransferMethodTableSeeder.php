<?php

use Illuminate\Database\Seeder;

class CashTransferMethodTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cash_transfer_method')->insert([
            [
                'method_id' => 1,
		        'method_type' => 'online_bank_transfer',
		        'company_id' => 1,
		        'cash_in_support' => 1,
		        'cash_out_support' => 1,
		        'cash_in_availability' => 1,	
		        'cash_out_availability' => 1,
    
            ],
        ]);
    }
}
