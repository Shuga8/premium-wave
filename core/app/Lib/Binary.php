<?php

namespace App\Lib;

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
        $this->coin_api_key = '51df7514-244b-43fc-a90a-0f53482fc699';
        $this->iex_api_key = 'pk_ec4702ee020546e68f094d6e2e99de4c';
        $this->fast_forex_api_key = '7300b3df0c-1a7889661d-sdqy7n`';
    }

    public function connectIexCloud(string $symbol)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.iex.cloud/v1/data/CORE/QUOTE/$symbol?token=$this->iex_api_key",
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

        if (!isset($response[0]['latestPrice'])) {
            Log::error('IEX Cloud response does not contain latestPrice');
            return null;
        }

        return $response[0]['latestPrice'];
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
            Log::error('Fast Forex response does not contain rate');
            return null;
        }

        return (float) $response['result']['rate'];
    }

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
                    $rate = (float) $this->connectFastForex($trade->crypto);
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
}
