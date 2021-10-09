<?php

namespace App\Traits;

trait response
{
    public static function success($message, $statusCode, $dataName = '', $data = ''){
        if($dataName == ''){
            return response()->json([
                'successful' => true,
                'message' => $message,
            ], $statusCode);
        } else {
            return response()->json([
                'successful' => false,
                'message' => $message,
                $dataName => $data,
            ], $statusCode);
        }
    }

    public static function falid($message, $statusCode, $status = 'E00'){
        return response()->json([
            'successful' => false,
            'status'     => $status,
            'message'    => $message,
        ], $statusCode);
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
        else
            return "";
    }
}