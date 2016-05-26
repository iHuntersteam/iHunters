<?php

namespace App\Helpers;


class ResponseHelper
{
    public static function makeResponse($responseData, $status = 200)
    {
        $respArray = [
            'data'          => [],
            'response_info' => [
                'error'        => false,
                'errorMessage' => 'no errors',
            ],
        ];

        if (isset($responseData['errorMessage'])) {
            $respArray['response_info']['error'] = true;
            $respArray['response_info']['errorMessage'] = $responseData['errorMessage'];
        }

        if (isset($responseData['data'])) {
            $respArray['data'] = $responseData['data'];
        }

        return response()->json($respArray, $status);
    }
}