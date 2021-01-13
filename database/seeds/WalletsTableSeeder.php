<?php

use Illuminate\Database\Seeder;

class WalletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wallet')->insert([
			[           
                'user_id' => 1,
                'type' => "PHP",
                'account_name' => null,
                'balance' => 100000.25,
                'created_at' => now(),
            ],

			[           
                'user_id' => 2,
                'type' => "PHP",
                'account_name' => null,
                'balance' => 100000.25,
                'created_at' => now(),
			],

            [                      
                'user_id' => 1,
                'account_name' => "asd-chad",
                'type' => "BTS",
                'balance' => null,
                'created_at' => now(),
            ],
			
        ]);

    }
}
