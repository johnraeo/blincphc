<?php

namespace App;

/**
 * This Class and function is called when creating a user
 * Uses the Nexmo\Client to send a message to nexmo to send an sms to the provided number
 * from the registered test number on the nexmo site
 * 
 * Please change the nexmo 'to' value to the number that you registered in the nexmo site
 */

class SendCode{
    public static function sendCode($phone){
        $code = rand(1111,9999);
        $nexmo = app('Nexmo\Client');
        $nexmo->message()->send([
            'to' => $phone,
            'from' => "Blinc.ph",
            'text' => 'Verification Code: '.$code
        ]);
        return $code;
    }
}

?>