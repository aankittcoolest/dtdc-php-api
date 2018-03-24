<?php

namespace App\Http\Controllers;

use  Illuminate\Http\Request;


class PhoneController extends Controller {

/**
 * This API accepts two parameters
 * phone-number: Format : 9112345678
 * code: Format: 1232456
 */
    public function sendCode(Request $request) {

        $phoneNumber = $request->json()->get('phone-number');
        $code = $request->json()->get('code');

        $apiKey = env('PHONE_API_KEY');
        $sender = env('PHONE_SENDER');

        $message = rawurlencode('Your verification code is : ' . $code );
    
        // Prepare data for POST request
        $data = array(
            'apiKey' => $apiKey,
            'numbers' => $phoneNumber,
            "sender" => $sender, 
            "message" => $message,
        "test" => "0");
    
        $ch = curl_init('http://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); // This is the result from the API
        curl_close($ch);

        $output = [
            'status' => $result==1 ? 'success': 'error',
            'phone-number' => $phoneNumber
        ];

        return json_encode($result);
    

    }
}