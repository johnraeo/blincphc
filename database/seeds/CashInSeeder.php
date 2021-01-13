<?php

use Illuminate\Database\Seeder;

class CashInSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Wallet\Cash_In::class, 2)->create();
    }
}
