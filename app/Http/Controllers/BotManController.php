<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;
use App\Models\Channel;

class BotManController extends Controller
{
    public function handle()
    {
        $request = request()->all();

        \Log::debug('request', $request);

        $botman = app('botman');

        $botman->listen();
    }

    protected function handleChannel($request, $tokenName)
    {
        if (isset($request['my_chat_member'])) {
            $chatChannel = $request['my_chat_member']['chat'];

            $existsChannel = Channel::where('channel_id', $chatChannel['id'])
                            ->where('bot_name', $tokenName)
                            ->exists();

            if ($existsChannel) {
                $existsChannel->delete();
            } else {
                Channel::create([
                    'channel_id' => $chatChannel['id'],
                    'channel_name' => $chatChannel['title'],
                    'bot_name' => $tokenName
                ]);
            }

        }
    }


    public function handleGroup()
    {
        $request = request()->all();

        $botman = app('botman-group');

        // $botman->on('new_chat_members', function ($payload, $bot) {
        //     foreach($payload as $user) {
        //         $fullName = $user['first_name'] . ' ' . $user['last_name'];
        //         $bot->reply('Chào mừng <b>' . $fullName . '</b> đã tham gia nhóm', [
        //             'parse_mode' => 'HTML'
        //         ]);
        //     }
        // });

        $botman->listen();

    }

    public function handleBot9()
    {
        $request = request()->all();

        $botman = app('botman-9');

        $botman->on('new_chat_members', function ($payload, $bot) {
            // foreach($payload as $user) {
            //     $fullName = $user['first_name'] . ' ' . $user['last_name'];
            //     $bot->reply('Chào mừng <b>' . $fullName . '</b> đã tham gia nhóm', [
            //         'parse_mode' => 'HTML'
            //     ]);
            // }
        });

        $botman->listen();
    }

    public function handleBotDragonsea()
    {
        $request = request()->all();

        $botman = app('dragonsea-bot');


        $botman->listen();
    }

    public function tinker()
    {
        return view('tinker');
    }


    public function groupTinker()
    {
        return view('group-tinker');
    }

    public function tinker9()
    {
        return view('tinker-9');
    }

    public function tinkerDragonsea()
    {
        return view('tinker-dragonsea');
    }

    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
