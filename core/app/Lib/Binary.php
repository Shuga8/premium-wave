<?php

namespace App\Lib;

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
        Log::info("can now update price is");
    }
}
