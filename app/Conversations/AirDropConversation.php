<?php

namespace App\Conversations;

use App\Models\AirDrop;
use App\Models\ReferralCode;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Str;

class AirDropConversation extends Conversation
{

    protected $address;

    public function run()
    {
        $this->showStep();
    }

    protected function showStep()
    {
        $message = '🔹 Total to earn per participant (if you complete all the tasks) = 100,000,000 ANM' . PHP_EOL .
            '🔹Per referral = 10,000,000 ANM' . PHP_EOL .
            '📢 Airdrop Rules' . PHP_EOL .
            PHP_EOL .
            '✏️ Mandatory Tasks:' . PHP_EOL .
            "🔹️ Join our Telegram <a href='https://t.me/aniworld_english'>Group</a>" . PHP_EOL .
            "🔹️ Join our Telegram <a href='https://t.me/animal_world_finance'>Channel</a>" . PHP_EOL .
            "🔹️ Follow our Twitter <a href='https://twitter.com/AnimalW89202427'>Twitter</a>" . PHP_EOL;

        $question = Question::create($message)
            ->addButtons([
                Button::create('📘  Submit my details')->value('next'),
                Button::create('🚫 Cancel')->value('cancel'),
            ]);


        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'next':
                        if (AirDrop::where(['chat_id' => $this->bot->getUser()->getId()])->exists()) {
                            return $this->say('ℹ️ You are the user of this bot already! ');
                        }
                        return $this->askBep20Address();
                    case 'cancel':
                        return $this->stopsConversation('Canceled');

                }
            }
        }, ['parse_mode' => 'HTML']);
    }

    protected function askBep20Address()
    {
        $message = '🔹️ Submit your BEP20 Wallet Address Please send me your BEP20 Wallet address to continue';

        $question = Question::create($message);


        return $this->ask($question, function (Answer $answer) {
            $text = $answer->getText();

            if (!Str::startsWith($text, '0x')) {
                $this->say('🚫 Wallet Address invalid. Try again!');

                return $this->askBep20Address();
            }

            $this->address = $text;

            return $this->saveInfo();

        }, ['parse_mode' => 'HTML']);
    }

    protected function saveInfo()
    {
        $botUser = $this->bot->getUser();

        $teleName = $botUser->getFirstName().' '.$botUser->getLastName();

        $data = [
            'tele_name' => $teleName,
            'chat_id' => $this->bot->getUser()->getId(),
            'address' => $this->address,
        ];

        $existsRef = ReferralCode::where('chat_id', $botUser->getId())->first();

        if ($existsRef) {
            $data['referral_code'] = $existsRef->code;
        }

        AirDrop::create($data);

        $refCode = app('referral_code')->encode($this->bot->getUser()->getId());

        $message = "🥳 Congratulations! We'll verify your social task. Follow us and 100,000,000 ANM tokens will unlock to your wallet soon" . PHP_EOL .
            PHP_EOL.
            "Don't forget to:" . PHP_EOL .
            '🔸️ Stay in the telegram channels' . PHP_EOL .
            '🔸️ Follow all the social media channels' . PHP_EOL .
            '🔸️ Visit our channels: ' . PHP_EOL .
            "<a href='https://www.aniworld.finance'>https://www.aniworld.finance</a>" . PHP_EOL .
            PHP_EOL .
            'Your personal referral link:' . PHP_EOL .
           'https://t.me/aniworld_airdrop_bot?start=' . $refCode;

        return $this->say($message, ['parse_mode' => 'HTML']);

    }
}
