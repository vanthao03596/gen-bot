<?php

namespace App\Jobs;

use App\Models\Channel;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class SendInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $resultId;

    public function __construct($resultId)
    {
        $this->resultId = $resultId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $botman = resolve('botman');


        if (DB::table('last_shows')->where('last_id', $this->resultId)->exists()) {
            return;
        }

        $result = DB::table('results')->where('id', $this->resultId)->first();

        $exploded = explode(' ', $result->traded);

        $tokenName = end($exploded);

        DB::table('last_shows')->insert([
            'last_id' => $result->id,
            'token_name' => $tokenName
        ]);

        $type = $result->type == 'sell' ? '👹 Sold' : '🚀 Bought';

        $message = $type . ' ' . $result->traded . ' (<strong>' . $result->value . '</strong>)' . PHP_EOL .
            PHP_EOL .
            ($result->type == 'sell' ? '💔' : '💚') . PHP_EOL .
            PHP_EOL .
            '1 ' . $tokenName . ' = ' . $result->token_price . PHP_EOL .
            PHP_EOL .
            '<a href="https://exchange.pancakeswap.finance/#/swap?outputCurrency=' . $result->address . '">🥞 Buy ' . $tokenName . '</a>' .
            ' | <a href="https://bscscan.com/tx/' . $result->tx_hash . '" >📶 Tx Hash </a>' .
            ' | <a href="https://charts.bogged.finance/?token=' . $result->address . '">💹 Chart </a>';


        $channelIds = Channel::pluck('channel_id')->toArray();

        $botman->say($message, $channelIds, TelegramDriver::class, ['parse_mode' => 'HTML']);
    }
}
