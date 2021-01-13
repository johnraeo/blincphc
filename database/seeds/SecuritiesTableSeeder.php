<?php

use Illuminate\Database\Seeder;

class SecuritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('securities')->insert([
			[           
	            'security_id' => 1,
                'user_id' => "1",
            ],
			[           
	            'security_id' => 2,
                'user_id' => "2",
            ],
			
        ]);
    }
}
