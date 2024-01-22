<?php

namespace App\Console\Commands;

use App\Models\ExchangeRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FetchApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'fetch currency api and save to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $fetchedRates = Http::get('https://api.coinbase.com/v2/exchange-rates');
        $exchangeRates = $fetchedRates->json()['data']['rates'];

        foreach ($exchangeRates as $code => $rate) {
            if(ExchangeRate::where('iso_code',$code)->exists()){
                ExchangeRate::where('iso_code',$code)->update(['rate' => $rate]);
            }else{
                ExchangeRate::create(['iso_code' => $code, 'rate'=> $rate]);
            }
        }

        return 0;
    }
}
