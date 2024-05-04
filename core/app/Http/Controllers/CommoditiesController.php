<?php

namespace App\Http\Controllers;

use App\Lib\Limit;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Commodity;
use App\Models\LimitTrade;
use Illuminate\Http\Request;

class CommoditiesController extends Controller
{

    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('user.login');
        }
        $commodities = Commodity::all();
        $user = auth()->user();
        $userData = User::where('id', $user->id)->firstOrFail();
        $balance = Wallet::where('user_id', $user->id)->where('currency_id', 3)->firstOrFail();
        $data = [
            'pageTitle' => 'Commodities',
            'user' => $userData,
            'balance' => $balance,
            'pair' => 'USD',
            'commodities' => $commodities
        ];
        return view($this->activeTemplate . 'commodity.index')->with($data);
    }

    public function store(Request $request)
    {
        $limit = new Limit(isCommodity: true);

        return $limit->store($request);
    }

    public function all()
    {

        $limits = LimitTrade::where("user_id", auth()->user()->id)->where('isCommodity', true)->get();
        return response()->json($limits);
    }

    public function cashout(int $id)
    {
        $notify[] = ['success', 'cashout successfull'];
        $limit = LimitTrade::where("user_id", auth()->user()->id)->where('id', $id)->where('isCommodity', true)->where('status', false)->firstOrFail();
        $balance = Wallet::where('user_id', auth()->user()->id)->where('currency_id', 3)->firstOrFail();

        $balance->balance += $limit->amount;

        $limit->status = 1;

        $balance->save();

        $limit->save();

        return redirect(route('commodity'))->withNotify($notify);
    }
}
