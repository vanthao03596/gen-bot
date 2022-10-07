<?php

namespace App\Console\Commands;

use App\Models\Channel;
use App\Models\Result;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Console\Command;

class SendInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sent notification to channel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $botman = resolve('botman');

        $result = Result::latest()->first();

        $exploded = explode(' ', $result->traded);

        $tokenName = end($exploded);

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
