<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
			[           
	            'email' => 'admin@blinc.ph',
				'access_level' => 4,
	            'password' => bcrypt('admin'),
	            'email_verified' => now(),
	            'provider' => '',
	            'secret_key' => random_int(100000,999999),
				'created_at' => now(),
				'updated_at' => now(),
	            'status' => 0,	
			],
			[           
	            'email' => 'test@blinc.ph',
				'access_level' => 4,
	            'password' => bcrypt('test'),
	            'email_verified' => now(),
	            'provider' => '',
	            'secret_key' => random_int(100000,999999),
				'created_at' => now(),
				'updated_at' => now(),
	            'status' => 0,	
			],
			
        ]);

    }
}
