<?php

namespace App\Helpers;


use Illuminate\Http\Request;

class RequestHelpers
{
    /**
     * Проверяет запрос на наличие необходимых параметров
     * @param Request $request
     * @param array $neededParams
     * @return string
     */
    public static function checkNeededParams(Request $request, array $neededParams)
    {
        $errorMessage = null;

        $errors = [];
        foreach ($neededParams as $neededParam) {
            if (!$request->has($neededParam)) {
                $errors[] = "Необходим параметр {$neededParam}";
            }
        }

        if (!empty($errors)) {
            $errorMessage = trim(implode($errors, ', '), ", ");
        }

        return $errorMessage;
    }
}