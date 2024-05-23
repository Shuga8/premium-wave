<?php

namespace App\Lib;

use App\Models\WaveLog;
use \Illuminate\Support\Facades\Log;

class Binary
{

    public $coin_api_key;
    public $iex_api_key;
    public $fast_forex_api_key;

    public function __construct()
    {
        $this->coin_api_key = '51df7514-244b-43fc-a90a-0f53482fc699';
        $this->iex_api_key = 'pk_ec4702ee020546e68f094d6e2e99de4c';
        $this->fast_forex_api_key = '7300b3df0c-1a7889661d-sdqy7n`';
    }

    public function updatePriceIs()
    {

        $trades = WaveLog::all()->where('status', 'runinng');

        foreach ($trades as &$trade) {
            if ($trade->isForex) {
                echo "Forex";
                echo " \n";
            } else if ($trade->isCrypto) {
            } else if ($trade->isStock) {
                $rate = (float) $this->connectIexCloud($trade->stock);
            } else if ($trade->isCommodity) {
                echo "Commodity";
                echo "\n";
            }
        }
        echo "all done";
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

        return $response[0]['latestPrice'];
    }
}
