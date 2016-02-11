<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * Build a JSON Response for an array object
     *
     * @param array $object
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function toJSONResponse(array $object, $statusCode = 200)
    {
        return response()->json($object, $statusCode);
    }
}
