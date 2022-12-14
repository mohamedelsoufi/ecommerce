<?php

namespace App\Traits;

trait response
{
    public static function success($message, $statusCode, $dataName = '', $data = ''){
        if($dataName == ''){
            return response()->json([
                'successful' => true,
                'message' => $message,
            ], $statusCode)->getData();
        } else {
            return response()->json([
                'successful' => true,
                'message' => $message,
                $dataName => $data,
            ], $statusCode)->getData();
        }
    }

    public static function faild($message, $statusCode, $status = 'E00'){
        return response()->json([
            'successful' => false,
            'status'     => $status,
            'message'    => $message,
        ], $statusCode)->getData();
    }

    public function getError($input)
    {
        if ($input == "E01")
            return 'authentication';

        else if ($input == "E02")
            return 'blocked';

        else if ($input == "E03")
            return 'validation';

        else if ($input == "E04")
            return 'not found';

        else if ($input == "E05")
            return 'not active';
        else if ($input == "E06")
            return 'expired';
        else
            return "";
    }
}