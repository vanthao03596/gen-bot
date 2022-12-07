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

        $message = '🎁Airdrop: <b>SAFU Santa (SSanta)</b>' . PHP_EOL .
            PHP_EOL . 
            '💰 Total Prize: 5000 $SSanta' . PHP_EOL .
            '👨‍💼 Luckies Winner: Top 500 Winners' . PHP_EOL .
            '👨‍💼 Top referrals : 5 NFTs' . PHP_EOL .
            '📅Distribution: 10 days after airdrop end' . PHP_EOL .
            PHP_EOL .

            '👥To participate: quote tweet pinned post (10 points).' . PHP_EOL .
            '1.Join Telegram Group (Need to complete captcha):' . PHP_EOL .
            "<a href='https://t.me/SAFUSanta'>https://t.me/SAFUSanta</a>" . PHP_EOL .
            PHP_EOL .
            "2.Join Telegram Channel :" . PHP_EOL .
            "<a href='https://t.me/SAFUSanta_Channel'>https://t.me/SAFUSanta_Channel</a>" . PHP_EOL .
            PHP_EOL .
            "3. Twitter : Like 3 tweets, Retweet pinned tweet, quote a tweet & comment something bullish about the project & tag 3 friends" . PHP_EOL .
            "<a href='https://twitter.com/safusanta'>https://twitter.com/safusanta</a>" . PHP_EOL .
            PHP_EOL .            

            "4. Fill up your answer" . PHP_EOL .
            PHP_EOL .

            '🔗Airdrop Link:' . PHP_EOL .
            "<a href='https://t.me/SafusantaAirdropBot'>https://t.me/SafusantaAirdropBot</a>" . PHP_EOL .
            PHP_EOL .

            'Your personal referral link:' . PHP_EOL .
            'https://t.me/SafusantaAirdropBot?start=' . $refCode . PHP_EOL;

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
        "🔹 Join our <a href='https://t.me/SAFUSanta_Channel'>Telegram Channel.</a>" . PHP_EOL .
        "🔹 Join our <a href='https://t.me/SAFUSanta'>Telegram Group.</a>" . PHP_EOL;

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
        'Like 3 tweets, Retweet pinned tweet, quote a tweet & comment something bullish about the project & tag 3 friends ' . PHP_EOL . 
        "<a href='https://twitter.com/safusanta'>https://twitter.com/safusanta</a>" . PHP_EOL .

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
           'https://t.me/SafusantaAirdropBot?start=' . $refCode;

        return $this->say($message, ['parse_mode' => 'HTML', 'disable_web_page_preview' => true]);

    }

    protected function getPreDetail()
    {
        return AirDrop9::where('chat_id', $this->bot->getUser()->getId())->first();
    }
}
