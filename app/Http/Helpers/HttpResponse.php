<?php

namespace App\Http\Helpers;

trait HttpResponse{

    function successOrFail($msg,$success,$todos,$codeStatus){
        return response()->json([
            "message" => $msg,
            "status" => $success,
            "data" => $todos
        ],$codeStatus);
    }
}