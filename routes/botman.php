<?php

use App\Conversations\AirDrop9Conversation;
use App\Conversations\AirDropConversation;
use App\Conversations\MenuConversation;
use App\Http\Controllers\BotManController;
use App\Models\ReferralCode;
use App\Models\ReferralCode9;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use Codenixsv\CoinGeckoApi\CoinGeckoClient;


$botmanAirDrop = resolve('botman');
$botman9 = resolve('botman-9');
$dragonsea = resolve('dragonsea-bot');

$botmanAirDrop->hears('/start', function ($bot) {
    $refCode = app('referral_code')->encode($bot->getUser()->getId());

    $message = 'Hello!' . PHP_EOL .
        PHP_EOL .
        '✅Please do the required tasks to be eligible to get airdrop tokens ' . PHP_EOL .
        PHP_EOL .
        '🔸For Joining - Get 100,000,000 ANM' . PHP_EOL .
        '⭐️ For each referral - Get 10,000,000 ANM' . PHP_EOL .
        PHP_EOL .
        '📘By Participating you are agreeing to the ANM Token (Airdrop) Program Terms and Conditions. Please see pinned post for more information.' . PHP_EOL .
        PHP_EOL .
        'Click "Join Airdrop" to proceed"' . PHP_EOL .
        PHP_EOL .
        'Your personal referral link:' . PHP_EOL .
        'https://t.me/aniworld_airdrop_bot?start=' . $refCode;

    $keyboard = Keyboard::create()->addRow(
        KeyboardButton::create('✅ Join Airdrop')->callbackData('/join')
    );

    $bot->reply($message, array_merge(
        ['parse_mode' => 'HTML'],
        $keyboard->toArray()
    ));
});

$botmanAirDrop->hears('/start {code}', function ($bot, $code) {
    $refCode = app('referral_code')->encode($bot->getUser()->getId());

    $existRefer = ReferralCode::where('chat_id', $bot->getUser()->getId())->first();

    if (!$existRefer) {
        ReferralCode::create([
            'chat_id' => $bot->getUser()->getId(),
            'code' => $code,
        ]);
    }

    $message = 'Hello!' . PHP_EOL .
        PHP_EOL .
        '✅Please do the required tasks to be eligible to get airdrop tokens ' . PHP_EOL .
        PHP_EOL .
        '🔸For Joining - Get 100,000,000 ANM' . PHP_EOL .
        '⭐️ For each referral - Get 10,000,000 ANM' . PHP_EOL .
        PHP_EOL .
        '📘By Participating you are agreeing to the ANM Token (Airdrop) Program Terms and Conditions. Please see pinned post for more information.' . PHP_EOL .
        PHP_EOL .
        'Click "Join Airdrop" to proceed"' . PHP_EOL .
        PHP_EOL .
        'Your personal referral link:' . PHP_EOL .
        'https://t.me/aniworld_airdrop_bot?start=' . $refCode;

    $keyboard = Keyboard::create()->addRow(
        KeyboardButton::create('✅ Join Airdrop')->callbackData('/join')
    );

    $bot->reply($message, array_merge(
        ['parse_mode' => 'HTML'],
        $keyboard->toArray()
    ));
});

$botmanAirDrop->hears('/join', function ($bot) {
    $bot->startConversation(new AirDropConversation);
});


$botmanAirDrop->hears('/stop', function ($bot) {
    $bot->reply('stopped');
})->stopsConversation();


$botman9->hears('/start', function ($bot) {
    $bot->startConversation(new AirDrop9Conversation);
});

$botman9->hears('/start {code}', function ($bot, $code) {
    $existRefer = ReferralCode9::where('chat_id', $bot->getUser()->getId())->first();

    $decodeChatId = app('referral_code')->decode($code);
    
    if (!$existRefer && ($decodeChatId != $bot->getUser()->getId())) {
        ReferralCode9::create([
            'chat_id' => $bot->getUser()->getId(),
            'code' => $code,
        ]);
    }
    $bot->startConversation(new AirDrop9Conversation);

});

$botman9->hears('/join', function ($bot) {
    $bot->startConversation(new AirDrop9Conversation);
});


$botman9->hears('/stop', function ($bot) {
    $bot->reply('stopped');
})->stopsConversation();


$dragonsea->hears('/social|/social@DragonSea_Bot', function (BotMan $bot) {
    $keyboard = Keyboard::create()->resizeKeyboard(true);;

    $keyboard->addRow(
        KeyboardButton::create('Website🌐')->url('https://dragonsea.io')
    );

    $keyboard->addRow(
        KeyboardButton::create('Announcement')->url('https://t.me/dragonsea_ann'),
        KeyboardButton::create('Global Group')->url('https://t.me/dragonsea_global'),
        KeyboardButton::create('Reddit')->url('https://www.reddit.com/r/dragonsea')
    );

    $keyboard->addRow(
        KeyboardButton::create('Twitter')->url('https://twitter.com/dragonseaio'),
        KeyboardButton::create('Discord')->url('https://discord.com/invite/ByA3qS5WYP'),
        KeyboardButton::create('Youtube')->url('https://www.youtube.com/channel/UCvG6cgjhBo6Y1i1CKlORQaA')
    );

    $bot->reply('Welcome to Dragonsea', array_merge(
        ['parse_mode' => 'HTML'],
        $keyboard->toArray()
    ));

});

$dragonsea->hears('/local|/local@DragonSea_Bot', function ($bot) {
    $keyboard = Keyboard::create()->resizeKeyboard(true);

    $keyboard->addRow(
        KeyboardButton::create('Global 🌐')->url('https://t.me/dragonsea_global'),
        KeyboardButton::create('Philippin 🇵🇭')->url('https://t.me/dragonsea_philippin'),
        KeyboardButton::create('Việt Nam 🇻🇳')->url('https://t.me/DragonSeaVietNam'),
        KeyboardButton::create('China 🇨🇳')->url('https://t.me/dragonsea_china')
    );

    $keyboard->addRow(
        KeyboardButton::create('Indonesia 🇮🇩')->url('https://t.me/dragonsea_indonesia'),
        KeyboardButton::create('Thailand 🇹🇭')->url('https://t.me/dragonsea_thailand'),
        KeyboardButton::create('Russia 🇷🇺')->url('https://t.me/dragonsea_russia'),
        KeyboardButton::create('Nigeria 🇳🇬')->url('https://t.me/dragonsea_nigeria')
    );

    $bot->reply('Choose group to continue', array_merge(
        ['parse_mode' => 'HTML'],
        $keyboard->toArray()
    ));
});


$dragonsea->hears('/buy|/buy@DragonSea_Bot', function ($bot) {
    $keyboard = Keyboard::create()->resizeKeyboard(true);

    $keyboard->addRow(
        KeyboardButton::create('PreSale')->url('https://sale.dragonsea.io/'),
        KeyboardButton::create('Buy DGE')->url('https://pancakeswap.finance/swap#/swap?inputCurrency=0x467f4773879a3917ddc2a6befa430c5d8ac22bee/'),
    );

    $bot->reply('Buy DGE', array_merge(
        ['parse_mode' => 'HTML'],
        $keyboard->toArray()
    ));
});

$dragonsea->hears('/contract|/contract@DragonSea_Bot', function ($bot) {
    $keyboard = Keyboard::create()->resizeKeyboard(true);

    $keyboard->addRow(
        KeyboardButton::create('DGE Contract')->url('https://bscscan.com/token/0x467f4773879a3917ddc2a6befa430c5d8ac22bee/'),
        KeyboardButton::create('DGG Contract')->url('https://bscscan.com/token/0x71cc884cdd3b67c1bda630d0677765e977bc11de/'),
    );

    $bot->reply('DGE Smart Contract', array_merge(
        ['parse_mode' => 'HTML'],
        $keyboard->toArray()
    ));
});
