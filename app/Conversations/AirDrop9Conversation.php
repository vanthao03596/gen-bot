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

        $message = 
            '🎉 CBP FINANCE AIRDROP Campaign 🎉' . PHP_EOL .
            'Just participate in our Airdrop and Get $15,000 $CBP' . PHP_EOL .
            '📌 Task:         ✅  $10 worth of $CBP for 1000 random participants each.' . PHP_EOL .
            PHP_EOL .
            '👨‍👩‍👧 Referral:   ✅  $5000 worth of $CBP for top 50 referrers.' . PHP_EOL .
            PHP_EOL .
            "1️⃣ Join CBP Finance Telegram Group (10 points). <a href='https://t.me/cbp_finance'>Link</a>" . PHP_EOL .
            "2️⃣ Follow CBP Finance on Twitter and Like, Cmt, retweet, Tag 3 friends in the pinned post (30 points). <a href='https://twitter.com/cbp_finance'>Link</a>" . PHP_EOL .
            "3️⃣ Follow CBP Telegram Channel (10 points).  <a href='https://t.me/cbpfinance_ann'>Link</a>" . PHP_EOL .
            "4️⃣ Enter your information to the airdrop bot." . PHP_EOL .
            "5️⃣ Share your referral link (50 points)." . PHP_EOL .
            PHP_EOL .

            '✏️ Notes: Total airdrop pool is $15,000 worth of $CBP.' . PHP_EOL .
            PHP_EOL .
            
            '🔗Airdrop Link:' . PHP_EOL .
            "<a href='https://t.me/CBPfinanceAirdropBot'>https://t.me/CBPfinanceAirdropBot</a>" . PHP_EOL .
            PHP_EOL .

            'Your personal referral link:' . PHP_EOL .
            'https://t.me/CBPfinanceAirdropBot?start=' . $refCode . PHP_EOL;

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
        "🔹 Join our <a href='https://t.me/cbpfinance_ann'>Telegram Channel.</a>" . PHP_EOL .
        "🔹 Join our <a href='https://t.me/cbp_finance'>Telegram Group.</a>" . PHP_EOL;

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
        'Follow CBP Finance on Twitter and Like, Cmt, retweet, Tag 3 friends in the pinned post' . PHP_EOL . 
        "<a href='https://twitter.com/cbp_finance'>https://twitter.com/cbp_finance</a>" . PHP_EOL .

        "Then submit your Twitter profile link below the chat :" . PHP_EOL .

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
            PHP_EOL .
            'Your personal referral link:' . PHP_EOL .
           'https://t.me/CBPfinanceAirdropBot?start=' . $refCode;

        return $this->say($message, ['parse_mode' => 'HTML', 'disable_web_page_preview' => true]);

    }

    protected function getPreDetail()
    {
        return AirDrop9::where('chat_id', $this->bot->getUser()->getId())->first();
    }
}
