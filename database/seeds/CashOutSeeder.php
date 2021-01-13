<?php

use Illuminate\Database\Seeder;

class CashOutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Wallet\Cash_Out::class, 2)->create();
    }
}
