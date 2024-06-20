<?php

namespace App\Lib;

use App\Models\Wallet;
use GuzzleHttp\Client;
use App\Models\WaveLog;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Log;

class Binary
{
    /**
     * 
     * Api keys for Fast Forex, Coin market cap, IEX cloud!
     * 
     * @var string
     */
    public $coin_api_key;
    public $iex_api_key;
    public $fast_forex_api_key;

    /**
     * Initialize api keys assignments
     * 
     * @return bool
     */
    public function __construct()
    {
        $this->coin_api_key = '326a6fd8-6f75-49c5-8acf-db35ebc6b31d';
        $this->iex_api_key = 'sk_4326a4d3e83449238d614b2d5d224b7d';
        $this->fast_forex_api_key = '3288e1c3b3-63f2c7b496-sev9t3';
    }

    public function getCryptoRate($symbol)
    {
        $url = "https://pro-api.coinmarketcap.com/v1/tools/price-conversion?amount=1&symbol=$symbol&convert=USD";

        $client = new Client();
        $response = $client->get($url, [
            'headers' => [
                'X-CMC_PRO_API_KEY' => $this->coin_api_key // Replace with your API key
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['data']['quote']['USD']['price'])) {
            $cryptoRate = $data['data']['quote']['USD']['price'];

            Log::info("Price for $symbol : $cryptoRate");
            return $cryptoRate;
        } else {
            Log::error("No price for $symbol");
        }
    }

    public function connectIexCloud(string $symbol)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.iex.cloud/v1/data/CORE/IEX_TOPS/$symbol?token=$this->iex_api_key",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Error decoding IEX Cloud response: ' . json_last_error_msg());
            return null;
        }

        // if (!isset($response[0]['latestPrice']) || ) {
        //     Log::error('IEX Cloud response does not contain latestPrice');
        //     return null;
        // }

        if ($response[0]["bidPrice"] != 0) {

            return $response[0]["bidPrice"];
        } else if ($response[0]["askPrice"] != 0) {

            return $response[0]["askPrice"];
        } else {
            return $response[0]["lastSalePrice"];
        }
    }


    public function connectFastForex(string $symbol)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.fastforex.io/convert?from=$symbol&to=USD&amount=1&api_key=$this->fast_forex_api_key",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $response = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Error decoding Fast Forex response: ' . json_last_error_msg());
            return null;
        }

        if (!isset($response['result']['rate'])) {
            Log::error('Fast Forex response does not contain rate for ' . $symbol);
            return null;
        }

        return (float) $response['result']['rate'];
    }

    /**
     * 
     * Update running trade's price_is value
     * 
     * @return bool
     */
    public function updatePriceIs()
    {
        $trades = WaveLog::where('status', 'running')->get();

        if ($trades->isEmpty()) {
            Log::info('No trades with status running found.');
            return false;
        }
        try {

            DB::beginTransaction();

            foreach ($trades as $trade) {
                Log::info('Processing trade ID: ' . $trade->id);

                if ($trade->isForex) {
                    $rate = (float) $this->connectFastForex($trade->currency);
                    $trade->price_is = $rate;
                } elseif ($trade->isCrypto) {
                    $rate = (float) $this->getCryptoRate($trade->crypto);
                    $trade->price_is = $rate;
                } elseif ($trade->isStock) {
                    $rate = (float) $this->connectIexCloud($trade->stock);
                    $trade->price_is = $rate;
                } elseif ($trade->isCommodity) {
                    $rate = (float) $this->connectIexCloud($trade->commodity);
                    $trade->price_is = $rate;
                }



                Log::info('Updated price for trade ID: ' . $trade->id . ' to ' . $trade->price_is);

                $trade->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }

    /**
     * Calculate and add pips to the amount
     * 
     * @return bool
     */
    public function setPips()
    {
        $trades = WaveLog::where('status', 'running')->get();

        try {


            DB::beginTransaction();

            foreach ($trades as $trade) {

                Log::info('Processing trade ID: ' . $trade->id);


                $pips = ((float) $trade->pips) * 10;
                
                if ($trade->amount <= 0) {
                    $bal = $trade->amount;
                    $this->updateStatusAndBalance($trade->id, $bal);
                    continue;
                }


                if ($trade->price_is >= $trade->take_profit ||  $trade->price_is <= $trade->stop_loss) {
                    $bal = $trade->amount;
                    $this->updateStatusAndBalance($trade->id, $bal);
                    continue;
                }
                if ($trade->price_is > $trade->price_was) {
                    $trade->amount += $pips;
                } else if ($trade->price_is < $trade->price_was) {
                    $trade->amount -= $pips;
                }

                $trade->price_was = $trade->price_is;

                $trade->save();
            }

            DB::commit();
            Log::info('pips calculated and completed');
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());
        }
    }



    /**
     *  change status to complete if take profit and stop loss conditions are met
     * credit the amount into user balance
     * @see undefined
     * @return bool
     */
    public function updateStatusAndBalance(int $id, float $balance)
    {

        $trade = WaveLog::where('id', $id)->first();
        $wallet = Wallet::where('user_id', $trade->user_id)->where('currency_id', 31)->where('wallet_type', 1)->first();

        try {

            DB::beginTransaction();

            Log::info('Processing trade ID: ' . $trade->id);

            $trade->status = 'completed';

            $wallet->balance += (float) $balance;

            $trade->save();

            $wallet->save();

            DB::commit();
            Log::info('Statuses and balances updated');
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    /**
     * 
     * check pending trades's open at price and see if it is equal or greater than symbols current rate
     * @see undefined
     * @return bool
     * @var object $trades
     * @var float $rate
     */
    public function checkPendingTradesAndUpdateStatusToRunning()
    {
        $trades = WaveLog::where('status', 'pending')->get();

        if ($trades->isEmpty()) {
            Log::info('No trades with status running found.');
            return false;
        }

        try {

            DB::beginTransaction();

            foreach ($trades as $trade) {
                Log::info('Processing pending trade ID: ' . $trade->id);

                if ($trade->isForex) {
                    $rate = (float) $this->connectFastForex($trade->currency);

                    if ($trade->open_at_is_set == 1 && $trade->open_at >= $rate) {
                        $trade->status = "running";
                        Log::info('Updated status for trade ID: ' . $trade->id . ' to running');
                    } else {
                        Log::info('Trade with trade ID: ' . $trade->id . ' has not met open at condition');
                    }

                    $trade->price_is = $rate;
                } elseif ($trade->isCrypto) {
                    $rate = (float) $this->getCryptoRate($trade->crypto);

                    if ($trade->open_at_is_set == 1 && $trade->open_at >= $rate) {
                        $trade->status = "running";
                        Log::info('Updated status for trade ID: ' . $trade->id . ' to running');
                    } else {
                        Log::info('Trade with trade ID: ' . $trade->id . ' has not met open at condition');
                    }

                    $trade->price_is = $rate;
                } elseif ($trade->isStock) {
                    $rate = (float) $this->connectIexCloud($trade->stock);

                    if ($trade->open_at_is_set == 1 && $trade->open_at >= $rate) {
                        $trade->status = "running";
                        Log::info('Updated status for trade ID: ' . $trade->id . ' to running');
                    } else {
                        Log::info('Trade with trade ID: ' . $trade->id . ' has not met open at condition');
                    }

                    $trade->price_is = $rate;
                } elseif ($trade->isCommodity) {
                    $rate = (float) $this->connectIexCloud($trade->commodity);

                    if ($trade->open_at_is_set == 1 && $trade->open_at >= $rate) {
                        $trade->status = "running";
                        Log::info('Updated status for trade ID: ' . $trade->id . ' to running');
                    } else {
                        Log::info('Trade with trade ID: ' . $trade->id . ' has not met open at condition');
                    }

                    $trade->price_is = $rate;
                }



                $trade->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info($e->getMessage());
        }
    }
}
