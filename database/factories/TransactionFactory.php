<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Wallet\Transactions;
use Faker\Generator as Faker;

$factory->define(Transactions::class, function (Faker $faker) {

    $method = ["cash_in", "cash_out", "send fund", "request fund"];
    $result = array_rand($method);
    $random_result = $method[$result];

    return [
        // 'cash_in_id' => factory(App\Wallet\Cash_In::class),
        // 'cash_out_id' => factory(App\Wallet\Cash_Out::class),
        // 'cash_out_id' => '',
        // 'transact_id' => random_int(100000,999999),
        'wallet_id' => '1',
        'amount' => $faker->randomFloat($nbMaxDecimals = 2, $min = 50, $max = 150),
        'status' => '1',
        'transact_type' => 'Test',
        'running_balance' => $faker->randomFloat($nbMaxDecimals = 2, $min = 50, $max = 150),
        'description' => '1:Dragonpay:0',
        'transact_date' => now(),
        'from_to' => 'sample@gmail.com'
    ];
});
