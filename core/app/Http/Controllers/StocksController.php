<?php

namespace App\Http\Controllers;

use App\Lib\Limit;
use App\Models\User;
use App\Models\Stock;
use App\Models\Wallet;
use App\Models\LimitTrade;
use Illuminate\Http\Request;

class StocksController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('user.login');
        }
        $stocks = Stock::all();
        $user = auth()->user();
        $userData = User::where('id', $user->id)->firstOrFail();
        $balance = Wallet::where('user_id', $user->id)->where('currency_id', 3)->firstOrFail();
        $data = [
            'pageTitle' => 'Stocks',
            'user' => $userData,
            'balance' => $balance,
            'pair' => 'USD',
            'stocks' => $stocks
        ];
        return view($this->activeTemplate . 'stock.index')->with($data);
    }

    public function store(Request $request)
    {
        $limit = new Limit(isStock: true);

        return $limit->store($request);
    }

    public function all()
    {

        $limits = LimitTrade::where("user_id", auth()->user()->id)->where('isStock', true)->get();
        return response()->json($limits);
    }

    public function cashout(int $id)
    {
        $notify[] = ['success', 'cashout successfull'];
        $limit = LimitTrade::where("user_id", auth()->user()->id)->where('id', $id)->where('isStock', true)->where('status', false)->firstOrFail();
        $balance = Wallet::where('user_id', auth()->user()->id)->where('currency_id', 3)->firstOrFail();

        $balance->balance += $limit->amount;

        $limit->status = 1;

        $balance->save();

        $limit->save();

        return redirect(route('stock'))->withNotify($notify);
    }
}
