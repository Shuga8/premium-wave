<?php

namespace App\Http\Controllers;

use App\Models\Commodity;
use App\Models\Currency;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Http\Request;

class WaveController extends Controller
{
    public function index()
    {

        if (!auth()->check()) {
            return redirect()->route('user.login');
        }

        $user = User::where('id', auth()->user()->id)->first();
        $stocks = Stock::all()->pluck('symbol');
        $commodites = Commodity::all()->pluck('symbol');
        $cryptos  = Currency::all()->where('type', 1)->pluck('symbol');
        $forexs = Currency::all()->where('type', 2)->pluck('symbol');

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
}
