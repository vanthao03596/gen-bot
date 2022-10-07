<?php

namespace App\Console\Commands;

use App\Jobs\SendInfo;
use Nesk\Puphpeteer\Puppeteer;

use Illuminate\Console\Command;
use Nesk\Rialto\Data\JsFunction;
use Illuminate\Support\Str;

class Crawler extends Command
{
    protected $signature = 'crawler';

    protected $description = 'Crawler Data';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $address = '0x8850d2c68c632e3b258e612abaa8fada7e6958e5';

        $puppeteer = new Puppeteer;

        $browser = $puppeteer->launch([
            'args' => ["--no-sandbox"],
            'ignoreHTTPSErrors' => true
        ]);

        $page = $browser->newPage();

        $page->setUserAgent('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36');

        $page->goto('https://charts.bogged.finance/?token=' . $address);

        $page->waitForSelector('tr.caption');

        $pageFunction = JsFunction::createWithParameters(['options'])
            ->body("return options.map(el => {
            let type = el.classList.contains('text-success-bright') ? 'buy' : 'sell'

            let link = el.querySelector('td a').href;

            let data = Array.from(el.querySelectorAll('td')).map(el => {
                return el.textContent.trim()
            })

            data.push(type)
            data.push(link)
            return data
           })");

        $tableData = $page->querySelectorAllEval('.tableContainer > table > tbody > tr', $pageFunction);

        $browser->close();

        $mapData = [];

        foreach($tableData as $data) {
            $link = $data[6];

            $txHash = Str::of($link)->after('/tx/')->__toString();

            $mapData[] = [
                'traded' => $data[1],
                'token_price' => $data[2],
                'value' => $data[3],
                'dex' => $data[4],
                'type' => $data[5],
                'tx_hash' => $txHash,
                'address' => $address,
            ];
        }

        \DB::table('results')->insertOrIgnore($mapData);

        $lastResult = \DB::table('results')->orderBy('created_at', 'desc')->first();

        SendInfo::dispatch($lastResult->id);
    }
}
