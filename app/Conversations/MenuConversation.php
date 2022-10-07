<?php

namespace App\Conversations;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

class MenuConversation extends Conversation
{
    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run()
    {
        $message = '<b>CHÀO MỪNG ĐẾN VỚI BOT PRO BITONO:</b>' . PHP_EOL .
            'Bot hỗ trợ canh tín hiệu gãy' . PHP_EOL .
            'Tự động vào lệnh theo thiết lập' . PHP_EOL .
            '+ Gấp win ' . PHP_EOL .
            '+ Martingle lose' . PHP_EOL .
            '+ Canh theo tín hiệu' . PHP_EOL .
            '+ Chốt lãi lỗ theo từng phiên nhỏ' . PHP_EOL .
            '<b>Quản lý tài khoản độc lập trên Telegram:</b>' . PHP_EOL .
            '+ Nạp tiền và xử lý hướng dẫn theo bot' . PHP_EOL .
            '+ Quản lý lịch sử giao dịch' . PHP_EOL .
            '+ /help để xem các hướng dẫn theo từng mục' . PHP_EOL;

        $keyboard = Keyboard::create()->addRow(
            KeyboardButton::create('👉 Menu hỗ trợ chức năng')->callbackData('Hi')
        );

        $this->say($message, array_merge(
            ['parse_mode' => 'HTML'],
            $keyboard->toArray()
        ));
    }
}
