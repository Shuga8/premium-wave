<?php

namespace App\Http\Controllers;

use App\Lib\Wave;
use App\Models\User;
use App\Models\Stock;
use App\Models\Wallet;
use App\Models\Currency;
use App\Models\Commodity;
use App\Models\WaveLog;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class WaveController extends Controller
{
    use HttpResponses;
    public function index()
    {

        if (!auth()->check()) {
            return redirect()->route('user.login');
        }

        $user = User::where('id', auth()->user()->id)->first();
        $stocks = Stock::all();
        $commodites = Commodity::all();
        $cryptos  = Currency::all()->where('type', 1);
        $forexs = Currency::all()->where('type', 2);


        $data = [
            'pageTitle' => 'Waves',
            'user' => $user,
            'stocks' => $stocks,
            'commodites' => $commodites,
            'cryptos' => $cryptos,
            'forexs' => $forexs
        ];

        return view($this->activeTemplate . 'wave.index')->with($data);
    }

    public function store(Request $request)
    {
        $wave = new Wave();
        return $wave->store($request);
    }

    public function getOpenTrades()
    {
        if (!auth()->check()) {
            return redirect()->route('user.login');
        }

        $trades = WaveLog::where('user_id', auth()->user()->id)->where('status', 'running')->get();

        return response()->json($trades);
    }

    public function getPendingTrades()
    {
        if (!auth()->check()) {
            return redirect()->route('user.login');
        }

        $trades = WaveLog::where('user_id', auth()->user()->id)->where('status', 'pending')->get();

        return response()->json($trades);
    }

    public function geTradesHistory()
    {
        if (!auth()->check()) {
            return redirect()->route('user.login');
        }

        $trades = WaveLog::where('user_id', auth()->user()->id)->where('status', 'completed')->get();

        return response()->json($trades);
    }
}
