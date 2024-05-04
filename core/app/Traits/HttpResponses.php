<?php

namespace App\Traits;

trait HttpResponses
{

    public function success($message)
    {
        return response()->json(['success' => $message]);
    }

    public function error($message)
    {
        return response()->json(['error' => $message]);
    }
}
