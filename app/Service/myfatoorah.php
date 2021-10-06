<?php

namespace App\Service;

use Illuminate\Http\Request;

class myfatoorah
{
    private $myfatooraBase;
    private $apiKey;

    public function __construct()
    {
        //Configurations
        $this->myfatooraBase = env("MYFATOORA_BASE");
        $this->apiKey        = env("MYFATOORA_API"); //Test token value to be placed here: https://myfatoorah.readme.io/docs/test-token
    }

    public function callAPI($endpointURL, $apiKey, $postFields = [], $requestType = 'POST') {
        $curl = curl_init($endpointURL);
        curl_setopt_array($curl, array(
            CURLOPT_CUSTOMREQUEST  => $requestType,
            CURLOPT_POSTFIELDS     => json_encode($postFields),
            CURLOPT_HTTPHEADER     => array("Authorization: Bearer $apiKey", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));
    
        $response = curl_exec($curl);
        $curlErr  = curl_error($curl);
    
        curl_close($curl);
    
        if ($curlErr) {
            //Curl is not working in your server
            die("Curl Error: $curlErr");
        }
    
        $error = $this->handleError($response);
        if ($error) {
            die("Error: $error");
        }
    
        return json_decode($response);
    }

    public function handleError($response) {

        $json = json_decode($response);
        if (isset($json->IsSuccess) && $json->IsSuccess == true) {
            return null;
        }

        //Check for the errors
        if (isset($json->ValidationErrors) || isset($json->FieldsErrors)) {
            $errorsObj = isset($json->ValidationErrors) ? $json->ValidationErrors : $json->FieldsErrors;
            $blogDatas = array_column($errorsObj, 'Error', 'Name');

            $error = implode(', ', array_map(function ($k, $v) {
                        return "$k: $v";
                    }, array_keys($blogDatas), array_values($blogDatas)));
        } else if (isset($json->Data->ErrorMessage)) {
            $error = $json->Data->ErrorMessage;
        }

        if (empty($error)) {
            $error = (isset($json->Message)) ? $json->Message : (!empty($response) ? $response : 'API key or API URL is not correct');
        }

        return $error;
    }

    //send request
    public function sendPayment($postFields) {
        $json = $this->callAPI($this->myfatooraBase . '/v2/SendPayment', $this->apiKey, $postFields);
        return $json->Data;
    }

    //get GetPaymentStatus
    public function GetPaymentStatus($postFields){
        $json = $this->callAPI($this->myfatooraBase . '/v2/getPaymentStatus', $this->apiKey, $postFields);
        return $json->Data;
    }
}
