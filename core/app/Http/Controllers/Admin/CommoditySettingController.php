<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CommoditySetting;
use App\Http\Controllers\Controller;

class CommoditySettingController extends Controller
{
    public function index()
    {
        $pageTitle = "Commodity Setting";
        $games     = CommoditySetting::oldest('id')->paginate(getPaginate());
        return view('admin.commodity_setting.index', compact('games', 'pageTitle'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'time' => 'required|integer',
            'profit' => 'required|numeric|max:100.00|min:0.00',
            'unit' => 'required|in:seconds,minutes,hours,days',
            'minimum_usd' => 'required|numeric',
        ]);

        if ($id) {
            $tradeSetting = CommoditySetting::findOrFail($id);
            $message      = "Commodity setting updated successfully";
        } else {
            $tradeSetting = new CommoditySetting();
            $message      = "Commodity setting successfully";
        }

        $tradeSetting->time = $request->time;
        $tradeSetting->profit = $request->profit;
        $tradeSetting->unit = $request->unit;
        $tradeSetting->minimum = json_encode([
            'USD' => $request->minimum_usd,
        ]);
        $tradeSetting->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $tradeSetting = CommoditySetting::findOrFail($id);
        $tradeSetting->delete();

        $notify[] = ['success', 'Time deleted successfully'];
        return back()->withNotify($notify);
    }
}
