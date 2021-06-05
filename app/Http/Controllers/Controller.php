<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Json response successfully
     *
     * @param string $message
     * @param [type] $data
     * @param integer $code
     * @return void
     */
    public function jsonResponse($message = "", $data = null, $code = 200)
    {
    	return response()->json([
    		"code" => $code,
    		"message" => $message,
    		"data" => $data
    	], 200);
    }
    /**
     * Erorr response
     *
     * @param [type] $userMessage
     * @param [type] $internalMessage
     * @param string $moreInfo
     * @param integer $code
     * @return void
     */
    public function errorResponse($userMessage, $internalMessage, $moreInfo = "", $code = 400)
    {
    	return response()->json([
    		"code" => $code,
    		"message" => $userMessage,
    		"errors" => [
    			"errorMessage" => $internalMessage,
    			"errorDetails" => $moreInfo,
    		]
    	], 200);
    }
}
