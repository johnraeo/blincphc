<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Wallet\Cash_In;
use Faker\Generator as Faker;

$factory->define(Cash_in::class, function (Faker $faker) {
    return [
        'cash_in_id' => random_int(100000,999999),
        'wallet_id' => '1',
        'cash_in_method' => 'm_lhullier',
        'amount' => $faker->randomFloat($nbMaxDecimals = 2, $min = 50, $max = 150),
        'transact_id' => factory(App\Wallet\Transactions::class),
        'transaction_fee' => $faker->randomFloat($nbMaxDecimals = 2, $min = 50, $max = 150),
        'reference_no' => $faker->randomNumber($ndDigits = 6),
        'status' => 1,
        'cash_in_date' => now(),
    ];
});
