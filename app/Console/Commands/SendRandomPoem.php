<?php

namespace App\Console\Commands;

use App\Models\Channel;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\Drivers\Telegram\TelegramDriver;
use Illuminate\Console\Command;

class SendRandomPoem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:poems';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $poems = [
            '
<b>Bài Quê Hương - Nguyễn Đình Huân 👍</b>
Quê hương là một tiếng ve
Lời ru của mẹ trưa hè à ơi
Dòng sông con nước đầy vơi
Quê hương là một góc trời tuổi thơ
Quê hương ngày ấy như mơ
Tôi là cậu bé dại khờ đáng yêu
Quê hương là tiếng sáo diều
Là cánh cò trắng chiều chiều chân đê
Quê hương là phiên chợ quê
Chợ trưa mong mẹ mang về bánh đa
Quê hương là một tiếng gà
Bình minh gáy sáng ngân nga xóm làng
Quê hương là cánh đồng vàng
Hương thơm lúa chín mênh mang trời chiều
Quê hương là dáng mẹ yêu
Áo nâu nón lá liêu siêu đi về
Quê hương nhắc tới nhớ ghê
Ai đi xa cũng mong về chốn xưa
Quê hương là những cơn mưa
Quê hương là những hàng dừa ven kinh
Quê hương mang nặng nghĩa tình
Quê hương tôi đó đẹp xinh tuyệt vời
Quê hương ta đó là nơi
Chôn rau cắt rốn người ơi nhớ về.',

            '<b>Bài Quê Hương Nỗi Nhớ - Hoàng Thanh Tâm 👍</b>
Trở về tìm mái nhà quê
Tìm hình bóng mẹ bộn bề nắng mưa
Tìm nắng xuyên ngọn cây dừa
Tìm hương mạ mới gió lùa thơm tho
Tìm đàn trâu với con đò
Áo bà ba mẹ câu hò trên sông
Nón lá nghiêng nắng nước ròng
Miền quê khó nhọc con còng con cua
Lục bình tim tím mùa mưa
Bồng bềnh một khúc sông khua mái chèo
Khói lên cháy bếp nhà nghèo
Con gà cục tác con mèo quẫy đuôi
Heo gà chạy ngược chạy xuôi
Chân bùn tay lấm nụ cười chân quê
Cánh cò trắng xóa vọng về
Ngân nga vọng cổ bốn bề thiên nhiên
Đậm đà ký ức giao duyên
Xương cha máu mẹ dịu hiền ca dao
Con dù biền biệt phương nào
Quê hương một dạ dạt dào khó phai.',
            '<b>Bài Thơ Lục Bát Miền Quê - Đức Trung 👍</b>
Tôi thầm nhớ một miền quê
Ước mơ thăm lại trở về tuổi thơ
Đồng xanh bay lả cánh cò
Hương sen tỏa ngát mộng mơ những chiều
Vi vu gió thổi sáo diều
Bóng ai như bóng mẹ yêu đang chờ?
Dòng sông, bến nước, con đò
Có người lữ khách bên bờ dừng chân
Xa xa vẳng tiếng chuông ngân
Bờ tre cuối xóm trong ngần tiếng chim
Tuổi thơ thích chạy trốn tìm
Cây đa giếng nước còn in trăng thề
Xa rồi nhớ mãi miền quê
Trong tim luôn nhắc trở về ngày xưa...
            ',
        ];

        $botman = resolve('botman');

        $message = OutgoingMessage::create($poems[array_rand($poems, 1)]);

        $channelIds = Channel::pluck('channel_id')->toArray();

        $botman->say($message, $channelIds, TelegramDriver::class, ['parse_mode' => 'HTML']);
    }
}
