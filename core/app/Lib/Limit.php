<?php

namespace App\Lib;

use App\Models\LimitTrade;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Limit
{

    use HttpResponses;

    protected $isCommodity;
    protected $isStock;

    public function __construct($isCommodity = false, $isStock = false)
    {
        $this->isCommodity = $isCommodity;

        $this->isStock = $isStock;
    }

    /** 
     * Store a Limit Trade
     *@param $request
     */
    public function store($request)
    {
        $id = auth()->user()->id;
        $user = User::where('id', $id)->firstOrFail();
        $balance = Wallet::where('user_id', $user->id)->where('currency_id', 3)->firstOrFail();

        if ($this->isCommodity) {
            $validator = Validator::make($request->all(), [
                'stop_loss' => ['required', 'numeric'],
                'take_profit' => ['required', 'numeric'],
                'rate' => ['required', 'numeric'],
                'percent' => ['required', 'numeric', 'min:0.1', 'max:100'],
                'commodity' => ['required', 'string', 'exists:commodities,symbol']
            ]);
        } elseif ($this->isStock) {
            $validator = Validator::make($request->all(), [
                'stop_loss' => ['required', 'numeric'],
                'take_profit' => ['required', 'numeric'],
                'rate' => ['required', 'numeric'],
                'percent' => ['required', 'numeric', 'min:0.1', 'max:100'],
                'stock' => ['required', 'string', 'exists:stocks,symbol']
            ]);
        }

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        $amount = ($request->percent / 100) * $balance->balance;

        $balance->balance -= $amount;

        try {
            DB::beginTransaction();

            $balance->save();

            $trade = new LimitTrade();

            $trade->user_id = $user->id;
            $trade->amount = $amount;
            $trade->stop_loss = $request->stop_loss;
            $trade->take_profit = $request->take_profit;
            $trade->price_was = $request->rate;
            $trade->wallet = "USD";
            $trade->isCommodity = $this->isCommodity;
            $trade->isStock = $this->isStock;
            if ($this->isCommodity) {
                $trade->commodity = $request->commodity;
            }
            if ($this->isStock) {
                $trade->stock = $request->stock;
            }

            $trade->save();

            DB::commit();

            return $this->success("Trade Set!");
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }
}
