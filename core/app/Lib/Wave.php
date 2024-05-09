<?php

namespace App\Lib;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;

class Wave
{

    public function __construct()
    {
    }

    public function trade($request)
    {

        $id = auth()->user()->id;
        $user = User::where('id', $id)->firstOrFail();
        $balance = Wallet::where('user_id', $user->id)->where('currency_id', 31)->where('wallet_type', 1)->firstOrFail()->pluck('balance');

        $validator = Validator::make($request->all(), [
            'stop_loss' => ['required', 'numeric'],
            'take_profit' => ['required', 'numeric'],
            'rate' => ['required', 'numeric'],
            'lotsize' => ['required', 'numeric'],
            'type' => ['required', 'string', 'in:crypto,currency,stock,commodity']
        ]);
    }
}
