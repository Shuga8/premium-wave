<?php

namespace App\Lib;

use App\Models\Wallet;
use GuzzleHttp\Client;
use App\Models\WaveLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Binary
{
    private $coin_api_key;
    private $iex_api_key;
    private $fast_forex_api_key;
    private $fmp_api_key;

    public function __construct()
    {
        $this->coin_api_key = '6567ef3d-f3e6-49f7-8d47-a17e0574d7f0';
        $this->iex_api_key = 'sk_4326a4d3e83449238d614b2d5d224b7d';
        $this->fast_forex_api_key = '1524f42cf8-1872de1e22-sfu1gi';
        $this->fmp_api_key = "cARpiP1yH7faNhSWqnQLyGNV0mc7oTxl";
    }

    private function getCryptoRate($symbol)
    {
        $url = "https://pro-api.coinmarketcap.com/v1/tools/price-conversion?amount=1&symbol=$symbol&convert=USD";
        $client = new Client();
        $response = $client->get($url, [
            'headers' => [
                'X-CMC_PRO_API_KEY' => $this->coin_api_key
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['data']['quote']['USD']['price'])) {
            $cryptoRate = $data['data']['quote']['USD']['price'];
            Log::info("Price for $symbol: $cryptoRate");
            return $cryptoRate;
        } else {
            Log::error("No price for $symbol");
            return null;
        }
    }

    private function connectIexCloud($symbol)
    {
        $url = "https://financialmodelingprep.com/api/v3/profile/$symbol?apikey=" . $this->fmp_api_key;
        $client = new Client();
        $response = $client->get($url);
        $data = json_decode($response->getBody(), true);

        if (isset($data[0]["price"])) {
            return $data[0]["price"];
        } else {
            Log::error("No price for $symbol");
            return null;
        }
    }

    private function connectFastForex($symbol)
    {
        $url = "https://api.fastforex.io/convert?from=$symbol&to=USD&amount=1&api_key=$this->fast_forex_api_key";
        $client = new Client();
        $response = $client->get($url);
        $data = json_decode($response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Error decoding Fast Forex response: ' . json_last_error_msg());
            return null;
        }

        if (!isset($data['result']['rate'])) {
            Log::error('Fast Forex response does not contain rate for ' . $symbol);
            return null;
        }

        return (float) $data['result']['rate'];
    }

    public function updatePriceIs()
    {
        $trades = WaveLog::where('status', 'running')->get();

        if ($trades->isEmpty()) {
            Log::info('No trades with status running found.');
            return false;
        }

        foreach ($trades as $trade) {
            try {
                DB::beginTransaction();
                Log::info('Processing trade ID: ' . $trade->id);

                $rate = null;
                if ($trade->isForex) {
                    $rate = $this->connectFastForex($trade->currency);
                } elseif ($trade->isCrypto) {
                    $rate = $this->getCryptoRate($trade->crypto);
                } elseif ($trade->isStock) {
                    $rate = $this->connectIexCloud($trade->stock);
                } elseif ($trade->isCommodity) {
                    $rate = $this->connectIexCloud($trade->commodity);
                }

                if ($rate !== null) {
                    $trade->price_is = $rate;
                    Log::info('Updated price for trade ID: ' . $trade->id . ' to ' . $trade->price_is);
                    $trade->save();
                    DB::commit();
                } else {
                    DB::rollBack();
                    Log::warning('Rate is null for trade ID: ' . $trade->id);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error processing trade ID ' . $trade->id . ': ' . $e->getMessage());
            }
        }

        return true;
    }

    public function setPips()
    {
        $trades = WaveLog::where('status', 'running')->get();
        $pipsMultiplier = config('trading.pips_multiplier', 10);

        foreach ($trades as $trade) {
            try {
                DB::beginTransaction();
                Log::info('Processing trade ID: ' . $trade->id);

                if (is_null($trade->price_is) || $trade->price_is === 0.00000000) {
                    Log::warning('Skipping trade ID: ' . $trade->id . ' due to null or zero price_is');
                    continue;
                }

                $pips = ((float) $trade->pips) * $pipsMultiplier;

                if ($trade->amount <= 0 || $trade->price_is >= $trade->take_profit || $trade->price_is <= $trade->stop_loss) {
                    $bal = $trade->amount;
                    $this->updateStatusAndBalance($trade->id, $bal);
                    DB::commit();
                    continue;
                }

                if ($trade->trade_type === "buy") {
                    $trade->amount += ($trade->price_is > $trade->price_was) ? $pips : -$pips;
                } else if ($trade->trade_type === "sell") {
                    $trade->amount += ($trade->price_is < $trade->price_was) ? $pips : -$pips;
                }

                $trade->price_was = $trade->price_is;
                $trade->save();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error processing trade ID ' . $trade->id . ': ' . $e->getMessage());
            }
        }

        Log::info('Pips calculated and completed');
    }

    public function updateStatusAndBalance($id, $balance)
    {
        $trade = WaveLog::find($id);
        $wallet = Wallet::where('user_id', $trade->user_id)
            ->where('currency_id', 31)
            ->where('wallet_type', 1)
            ->first();

        try {
            DB::beginTransaction();
            Log::info('Processing trade ID: ' . $trade->id);

            $trade->status = 'completed';
            $wallet->balance += (float) $balance;

            $trade->save();
            $wallet->save();

            DB::commit();
            Log::info('Statuses and balances updated');
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating status and balance for trade ID ' . $trade->id . ': ' . $e->getMessage());
            return false;
        }
    }

    public function checkPendingTradesAndUpdateStatusToRunning()
    {
        $trades = WaveLog::where('status', 'pending')->get();

        if ($trades->isEmpty()) {
            Log::info('No trades with status pending found.');
            return false;
        }

        try {
            DB::beginTransaction();

            foreach ($trades as $trade) {
                Log::info('Processing pending trade ID: ' . $trade->id);

                $rate = null;
                if ($trade->isForex) {
                    $rate = $this->connectFastForex($trade->currency);
                } elseif ($trade->isCrypto) {
                    $rate = $this->getCryptoRate($trade->crypto);
                } elseif ($trade->isStock) {
                    $rate = $this->connectIexCloud($trade->stock);
                } elseif ($trade->isCommodity) {
                    $rate = $this->connectIexCloud($trade->commodity);
                }

                if ($rate !== null) {
                    if ($trade->open_at_is_set == 1 && $trade->open_at >= $rate) {
                        $trade->status = "running";
                        Log::info('Updated status for trade ID: ' . $trade->id . ' to running');
                    } else {
                        Log::info('Trade with trade ID: ' . $trade->id . ' has not met open at condition');
                    }
                    $trade->price_is = $rate;
                    $trade->save();
                } else {
                    Log::warning('Rate is null for pending trade ID: ' . $trade->id);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating pending trades to running: ' . $e->getMessage());
        }

        return true;
    }
}
