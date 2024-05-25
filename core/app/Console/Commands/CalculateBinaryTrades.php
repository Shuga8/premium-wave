<?php

namespace App\Console\Commands;

use App\Lib\Binary;
use Illuminate\Console\Command;

class CalculateBinaryTrades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'binary:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate binary Methods';

    /**
     * Execute the console command.
     *
     * @return int 
     *
     */
    public function handle()
    {
        $binary = new Binary();

        $binary->updatePriceIs();

        $this->info('binary calculation fot this instance is successfull');
    }
}
