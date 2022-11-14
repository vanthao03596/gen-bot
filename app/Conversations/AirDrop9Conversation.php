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
            '<img src="https://im5.ezgif.com/tmp/ezgif-5-6cac9ee5f6.gif"></img>' . PHP_EOL . 
            'ℹ️ Tate Socical :' . PHP_EOL .
            'The freedom Social-Finance of Tate brothers & Fans. "Tate Social" is a Social-Fi with the application of blockchain technology and Web3 that we have heard a lot about in recent times !' . PHP_EOL .
            PHP_EOL .
            'Tate Social - $TOPG is also doing Pre-sale (Pinksale Fairlaunch) on November 18th ! ' . PHP_EOL .
            PHP_EOL .

            'If you know previous projects about Tate Brothers like: Tate token has ATH 60x, or TOPG coin has ATH 30x?' . PHP_EOL .
            'Tate Social - $TOPG will be a 100x project you don\'t want to miss in this 2022.' . PHP_EOL .
            PHP_EOL .
            "🌐 Website             : <a href='https://tate.social/'>https://tate.social/</a>" . PHP_EOL .
            "🔖 Project details     : <a href='https://page.tate.social/'>https://page.tate.social/</a>" . PHP_EOL .
            PHP_EOL .
            '📢 Airdrop Info: Total 15000 $TOPG Token' . PHP_EOL .
            PHP_EOL .            

            '🏆 Task:       ➕  $10 worth of $TOPG for 1000 random participants each.' . PHP_EOL .
            '👨‍👩‍👧 Referral:   ➕  $5000 worth of $TOPG for top 100 referrers.' . PHP_EOL .
            PHP_EOL .

            "🗓 Airdrop will end on 24 th November & distribution begins 10 days after airdrop end" . PHP_EOL .
            PHP_EOL .

            "Please complete the following tasks to be eligible for the airdrop." . PHP_EOL .
            PHP_EOL .
            
            '🔹 Join our Telegram Group (10 points)' . PHP_EOL .
            "<a href='https://t.me/TateSocial_TOPG_Ann'>https://t.me/TateSocial_TOPG_Ann</a>" . PHP_EOL .
            "🔹 Join our Telegram Channel (10 points)" . PHP_EOL .
            "<a href='https://t.me/Tatesocial_TOPG_Global'>https://t.me/Tatesocial_TOPG_Global</a>" . PHP_EOL .
            '🔹 Follow us on Twitter, like, quote tweet pinned post "including hashtag #TateSocial #TOPGcoin #PresaleNov2022" (10 points). https://twitter.com/tatesocial_topg' . PHP_EOL . 
            '🔹 Tag 5 friends "including hashtag #TateSocial #TOPGcoin #Nov2022" on our twitter pinned post (5 points)' . PHP_EOL .
            '🔹 Submit your BSC BEP-20 wallet address.' . PHP_EOL . 
            PHP_EOL .

            'Your personal referral link:' . PHP_EOL .
            'https://t.me/TateSocial_Airdrop_Bot?start=' . $refCode . PHP_EOL .
            PHP_EOL .

            'Click "Submit my details" to submit your details to verify whether you completed all the tasks or not.' . PHP_EOL .
            "(Tate Social also have Active & shilling contest, check it out on our TG for joining)";

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
        "🔹 Join our <a href='https://t.me/TateSocial_TOPG_Ann'>Telegram Channel.</a>" . PHP_EOL .
        "🔹 Join our <a href='https://t.me/Tatesocial_TOPG_Global'>Telegram Group.</a>" . PHP_EOL;

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
        '🔹 Follow us on Twitter, like, quote tweet pinned post "including hashtag #TateSocial #TOPGcoin #PresaleNov2022". https://twitter.com/tatesocial_topg' . PHP_EOL . 
        '🔹 Tag 5 friends "including hashtag #TateSocial #TOPGcoin #Nov2022" on our twitter pinned post' . PHP_EOL .

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
            "<a href='https://tate.social/'>https://tate.social/</a>" . PHP_EOL .
            PHP_EOL .
            'Your personal referral link:' . PHP_EOL .
           'https://t.me/TateSocial_Airdrop_Bot?start=' . $refCode;

        return $this->say($message, ['parse_mode' => 'HTML', 'disable_web_page_preview' => true]);

    }

    protected function getPreDetail()
    {
        return AirDrop9::where('chat_id', $this->bot->getUser()->getId())->first();
    }
}
