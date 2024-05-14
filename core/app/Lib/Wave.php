<?php

namespace App\Lib;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WaveLog;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Wave
{
    use HttpResponses;

    public function __construct()
    {
    }

    public function store($request)
    {

        $id = auth()->user()->id;
        $user = User::where('id', $id)->firstOrFail();
        $balance = Wallet::where('user_id', $user->id)->where('currency_id', 31)->where('wallet_type', 1)->first();

        $validator = Validator::make($request->all(), [
            'stop_loss' => ['required', 'numeric'],
            'take_profit' => ['required', 'numeric'],
            'rate' => ['required', 'numeric'],
            'lotsize' => ['required', 'numeric'],
            'type' => ['required', 'string', 'in:crypto,currency,stock,commodity'],
            'symbol' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $lotsize = $request->lotsize * 10;

        $pips = $lotsize;

        $amount = ($lotsize / 100) * $balance->balance;

        if ($lotsize * 53.87 > $balance->balance) {
            return $this->error("wallet balance must be greater than required margin!");
        }


        try {

            DB::beginTransaction();

            $balance->balance -= $amount;
            $wave = new  WaveLog();

            $wave->user_id = $user->id;
            $wave->order_id =  time() . rand(111111, 9999999);
            $wave->amount = $amount;
            $wave->open_amount = $amount;
            $wave->open_price = $request->rate;
            $wave->stop_loss = $request->stop_loss;
            $wave->take_profit = $request->take_profit;
            $wave->pips = $pips;
            $wave->price_was = $request->rate;
            if ($request->type == "currency") {
                $wave->isForex = true;
                $wave->currency = $request->symbol;
            } else if ($request->type == "crypto") {
                $wave->isCrypto = true;
                $wave->crypto = $request->symbol;
            } else if ($request->type == "stock") {
                $wave->isStock = true;
                $wave->stock = $request->symbol;
            }
            if ($request->type == "commodity") {
                $wave->isCommodity = true;
                $wave->commodity = $request->symbol;
            }
            if ($request->has('open_at') && !is_null($request->open_at) && !empty($request->open_at)) {
                $wave->open_at = $request->open_at;
                $wave->status = 'pending';
            } else {
                $wave->status = 'running';
            }

            $wave->save();
            $balance->save();
            DB::commit();

            return $this->success("Trade successfuly placed");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
