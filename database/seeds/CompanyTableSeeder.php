<?php

use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company')->insert([
            [
                'company_id' => 1,
		        'company_name' => 'Union Bank',
		        'image' => '',
		        'status' => 0,
		        'region' => 'RandomText',
    
            ],
        ]);
    }
}
