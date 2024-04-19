<?php

namespace App;

use GuzzleHttp\Client;

class Helper
{
    protected $client;
    // public function __construct()
    // {
    //     // Initialize the Guzzle HTTP client with the MSEGAT API URL and your API key
    //     $this->client = new Client([
    //         'base_uri' => 'https://www.msegat.com/gw/getMessages.php',
    //         'query' => [
    //             'apiKey' => 'your_api_key_here',
    //         ],
    //     ]);
    // }


        public static function sendOtpSms($to, $otp)
        {
            // Initialize the Guzzle HTTP client with the MSEGAT API URL and your API key
            $client = new Client([
                'base_uri' => 'https://www.msegat.com/gw/getMessages.php',
                'query' => [
                    'apiKey' => '3f3d19b10e049d1a4cc7f760d2e962a3',
                    'numbers' => $to,
                    'msg' => "Your OTP is: {$otp}",
                    'userSender' => 'MSEGAT.COM',
                    'type' => '2',
                    "userName"=>"masasoft"
                ],
            ]);
    
            // Send an HTTP request to the MSEGAT API to send an OTP SMS message
            $response = $client->request('POST', 'https://www.msegat.com/gw/getMessages.php');
           //   dd($response);
    
            // Check if the request was successful and return the response
            if ($response->getStatusCode() == 200) {
                return true;
            } else {
                return false;
            }
            
        }

        public static function getBalance()
        {
            // Initialize the Guzzle HTTP client with the MSEGAT API URL and your API key
            $client = new Client([
                'base_uri' => 'https://www.msegat.com/gw/Credits.php',
                'query' => [
                    'apiKey' => '3f3d19b10e049d1a4cc7f760d2e962a3',
                    "userName"=>"masasoft",
                    "msgEncoding "=>"utf8",
                ],
            ]);
    
            // Send an HTTP request to the MSEGAT API to get the account balance
            $response = $client->request('POST', 'https://www.msegat.com/gw/Credits.php');
    
            // Check if the request was successful and return the balance value
            if ($response->getStatusCode() == 200) {
                return $response->getBody()->getContents();
            } else {
                return false;
            }
        }
    
    

    // Add more helper functions as needed...
}