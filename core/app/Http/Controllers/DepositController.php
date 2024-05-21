<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index(Request $request)
    {

        $data = [
            'pageTitle' => 'Deposit',
            'amount' => $request->amount
        ];

        return view($this->activeTemplate . 'user.deposit')->with($data);
    }

    public function store(Request $request)
    {
    }
}
