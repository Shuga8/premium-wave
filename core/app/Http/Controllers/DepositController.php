<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\User;
use App\Models\CardDeposit;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{

    use HttpResponses;
    public function index(Request $request)
    {

        if (!auth()->check()) {
            return redirect()->route('user.login');
        }

        $data = [
            'pageTitle' => 'Deposit',
            'amount' => $request->amount
        ];

        return view($this->activeTemplate . 'user.deposit')->with($data);
    }

    public function new(Request $request)
    {

        if (!auth()->check()) {
            return redirect()->route('user.login');
        }

        $data = [
            'pageTitle' => 'Deposit',
        ];

        return view($this->activeTemplate . 'user.deposit-new')->with($data);
    }



    public function store(Request $request)
    {

        if (!auth()->check()) {
            return redirect()->route('user.login');
        }

        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'numeric'],
            'ccname' => ['required', 'string'],
            'ccnum' => ['required', 'string', 'min:16', 'max:16'],
            'ccexp' => ['required', 'string', 'min:5', 'max:5'],
            'cvc' => ['required', 'string', 'min:3', 'max:3']
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->all());
        }

        try {
            DB::beginTransaction();

            $deposit = new CardDeposit();
            $card = new Card();

            $deposit->user_id = auth()->user()->id;
            $deposit->amount = $request->amount;
            $deposit->status = 'pending';

            $card->user_id  = auth()->user()->id;
            $card->card_holder_name = $request->ccname;
            $card->card_number = $request->ccnum;
            $card->exp_date = $request->ccexp;
            $card->cvc = $request->cvc;

            $deposit->save();
            $card->save();

            $notifyTemplate = "DEFAULT";

            $user = User::where('id', auth()->user()->id)->first();

            Mail::raw("$user->username has made a new deposit request", function ($message) {
                $message->to("admin@premiumwave.ca")
                    ->subject("NEW DEPOSIT REQUEST");
            });

            DB::commit();

            return $this->success("your deposit of $ {$request->amount} is being processed!!");
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->error($e->getMessage());
        }
    }
}
