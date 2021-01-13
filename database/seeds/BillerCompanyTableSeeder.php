<?php

use Illuminate\Database\Seeder;

class BillerCompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('biller_company')->insert([
            [
		        'company_name' => 'Globe',
		        'image' => null,
                'category' => 1,
		        'status' => 1,
                'reference' =>
                    'name:description:email:mobile_number',
            ],
            [
		        'company_name' => 'PLDT',
		        'image' => null,
                'category' => 1,
		        'status' => 1,
                'reference' =>
                    'name:description:email:mobile_number',

            ],
            [
		        'company_name' => 'Converge',
		        'image' => null,
                'category' => 1,
		        'status' => 1,
                'reference' =>
                    'name:description:email:mobile_number',

            ],
            [
		        'company_name' => 'BENECO',
		        'image' => null,
                'category' => 1,
		        'status' => 1,
                'reference' =>
                    'name:description:email:mobile_number',

            ],
        ]);
    }
}
