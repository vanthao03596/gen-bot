<?php

namespace App\Conversations;

use App\Models\AirDrop;
use App\Models\AirDrop9;
use App\Models\ReferralCode;
use App\Models\ReferralCode9;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Str;

class AirDrop9Conversation extends Conversation
{

    protected $address;
    
    protected $twitter;

    protected $reddit;

    protected $discord;

    public function run()
    {
        $this->showStep();
    }

    protected function showStep()
    {
        $refCode = app('referral_code')->encode($this->bot->getUser()->getId());

        $botUser = $this->bot->getUser();

        $teleName = $botUser->getFirstName().' '.$botUser->getLastName();

        $message = 'Dear ' . $teleName . '! I am your friendly "Genesis Defi by Floki" Airdrop Bot.' . PHP_EOL .
            PHP_EOL .
            'ℹ️ Genesis Defi by Floki - Symbol "GenF", New generation of multi-data decentralized finance, leading the new revolution of the decentralized finance movement. By combining the Big-data resource with tools to support storage (Staking), exchange cryptocurrency (DEX), the uniqueness of NFT.' . PHP_EOL .
            PHP_EOL .
            "We are also doing our Pre-sale (Fairlaunch on Pinksale) at October 12th !" . PHP_EOL .
            PHP_EOL .
            "🌐 Website: <a href='https://genesisdefibyfloki.net'>https://genesisdefibyfloki.net</a>" . PHP_EOL .
            PHP_EOL .
            '📢 Airdrop Info: Total 15000 $BUSD +  $ "GenF" Token (For top 500 winners)' . PHP_EOL .
            "💰 For joining: Get 20$" . PHP_EOL .
            "💰 For each referral: Get 10$ in GenF  (Get tokens for each referral and up to 1,000 referrals.)" . PHP_EOL .
            PHP_EOL .

            "🗓 Airdrop will end on 18 th October & distribution begins." . PHP_EOL .
            PHP_EOL .
            "🔝 Top 1000 with most referrals and 4000 lucky participants will receive the airdrop." . PHP_EOL .
            PHP_EOL .

            "Please complete the following tasks to be eligible for the airdrop." . PHP_EOL .
            PHP_EOL .

            "🔹️ Join our <a href='https://t.me/GenesisDefibyFloki_Global'>Telegram Group (10 points)</a>" . PHP_EOL .
            "🔹️ Join our <a href='https://t.me/GenesisDefibyFloki_Ann'>Telegram Channel (10 points)</a>" . PHP_EOL .
            "🔹️ Follow us on <a href='https://twitter.com/GenesisFloki'>Twitter</a>, quote tweet pinned post (10 points)." . PHP_EOL.
            "🔹️ Tag 3 friends on our twitter pinned post (5 points)" . PHP_EOL .
            "🔹️ Submit your BSC BEP-20 wallet address." . PHP_EOL .

            PHP_EOL .

            'Your personal referral link:' . PHP_EOL .
           'https://t.me/GenesisDefi_Airdrop_Bot?start=' . $refCode . PHP_EOL .
           PHP_EOL .

           'Click "Submit my details" to submit your details to verify whether you completed all the tasks or not.';

        $question = Question::create($message)
            ->addButtons([
                Button::create('📘  Submit my details')->value('next'),
            ]);


        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'next':
                        return $this->askJoinTelegram();
                    case 'cancel':
                        return $this->bot->startConversation(new AirDrop9Conversation);

                }
            }
        }, ['parse_mode' => 'HTML', 'disable_web_page_preview' => true]);
    }

    protected function askJoinTelegram()
    {
        $botUser = $this->bot->getUser();

        $teleName = $botUser->getFirstName().' '.$botUser->getLastName();

        $message = $teleName.', Now' . PHP_EOL .
        "🔹 Join our <a href='https://t.me/GenesisDefibyFloki_Ann'>Telegram Channel.</a>" . PHP_EOL .
        "🔹 Join our <a href='https://t.me/GenesisDefibyFloki_Global'>Telegram Group.</a>" . PHP_EOL;

        $question = Question::create($message)
            ->addButtons([
                Button::create('✅ Done')->value('next'),
                Button::create('🚫 Cancel')->value('cancel'),
            ]);


        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'next':
                        return $this->asJoinTwitter();
                    case 'cancel':
                        return $this->bot->startConversation(new AirDrop9Conversation());
                }
            }
        }, ['parse_mode' => 'HTML', 'disable_web_page_preview' => true]);
    }

    protected function asJoinTwitter()
    {
        $botUser = $this->bot->getUser();

        $teleName = $botUser->getFirstName().' '.$botUser->getLastName();

        $message = $teleName.', Now' . PHP_EOL .
        "🔹 Follow us on Twitter  <a href='https://twitter.com/GenesisFloki'>Twitter</a>, quote tweet pinned post & tag 3 friends." . PHP_EOL .

        "Then submit your Twitter profile link:" . PHP_EOL .

        "(Example: <a href='https://twitter.com/yourusername'>https://twitter.com/yourusername</a>". PHP_EOL.
        PHP_EOL .

        "Previous Detail:" . PHP_EOL .

        optional($this->getPreDetail())->twitter;

        $question = Question::create($message)
            ->addButtons([
                Button::create('🚫 Cancel')->value('cancel'),
            ]);


        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'cancel':
                        return $this->bot->startConversation(new AirDrop9Conversation());
                }
            } else {
                $text = $answer->getText();

                $this->twitter = $text;

                return $this->askBep20Address();
            }
        }, ['parse_mode' => 'HTML', 'disable_web_page_preview' => true]);
    }

    protected function askBep20Address()
    {
        $message = '🔹️ Submit your BEP20 Wallet Address Please send me your BEP20 Wallet address to continue' . PHP_EOL .
        PHP_EOL .
        "Previous Detail:" . PHP_EOL .

        optional($this->getPreDetail())->address;

        $question = Question::create($message)
            ->addButtons([
                Button::create('🚫 Cancel')->value('cancel'),
            ]);


        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                switch ($answer->getValue()) {
                    case 'cancel':
                        return $this->bot->startConversation(new AirDrop9Conversation());
                }
            } else {
                $text = $answer->getText();

                if (!Str::startsWith($text, '0x')) {
                    $this->say('🚫 Wallet Address invalid. Try again!');

                    return $this->askBep20Address();
                }

                $this->address = $text;

                return $this->saveInfo();
            }

        }, ['parse_mode' => 'HTML', 'disable_web_page_preview' => true]);
    }

    protected function saveInfo()
    {
        $botUser = $this->bot->getUser();

        $teleName = $botUser->getFirstName().' '.$botUser->getLastName();

        $data = [
            'tele_name' => $teleName,
            'twitter' => $this->twitter,
            'address' => $this->address,
        ];

        $existsRef = ReferralCode9::where('chat_id', $botUser->getId())->first();

        if ($existsRef) {
            $data['referral_code'] = $existsRef->code;
            $data['refer_chat_id'] = app('referral_code')->decode($existsRef->code);
        }

        AirDrop9::updateOrCreate(
            ['chat_id' => $this->bot->getUser()->getId()], 
            $data
        );

        $refCode = app('referral_code')->encode($this->bot->getUser()->getId());

        $message = "🥳 Congratulations! We'll verify your social task" . PHP_EOL .
            PHP_EOL.
            "🙏 Thank you for participating in our airdrop. Please do not leave any of our social media platforms." . PHP_EOL .
            "<a href='https://genesisdefibyfloki.net/'>https://genesisdefibyfloki.net/</a>" . PHP_EOL .
            PHP_EOL .
            'Your personal referral link:' . PHP_EOL .
           'https://t.me/GenesisDefi_Airdrop_Bot?start=' . $refCode;

        return $this->say($message, ['parse_mode' => 'HTML', 'disable_web_page_preview' => true]);

    }

    protected function getPreDetail()
    {
        return AirDrop9::where('chat_id', $this->bot->getUser()->getId())->first();
    }
}
