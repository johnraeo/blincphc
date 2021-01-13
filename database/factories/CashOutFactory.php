<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Wallet\Cash_Out;
use Faker\Generator as Faker;

$factory->define(Cash_Out::class, function (Faker $faker) {
    return [
        'cash_out_id' => random_int(100000,999999),
        'wallet_id' => '1',
        // 'recipient_first_name' => $faker->firstName,
        // 'recipient_last_name' => $faker->lastName,
        'phone_no' => $faker->e164PhoneNumber,
        'amount' => $faker->randomFloat($nbMaxDecimals = 2, $min = 50, $max = 150),
        'proof_of_id' => $faker->word,
        'cash_out_method' => 'Dragonpay',
        'transact_id' => factory(App\Wallet\Transactions::class),
        'transaction_fee' => $faker->randomFloat($nbMaxDecimals = 2, $min = 50, $max = 150),
        'reference_no' => $faker->randomNumber($nbDigits = 6),
        'cash_out_date' => now(),
    ];
});
