<?php


namespace App\Services;


class InviteCode
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var int
     */
    private $octal;

    /**
     * InviteCode constructor.
     *
     * @param  string  $key
     */
    public function __construct(string $key = 'abcdefghjkmnpqrstuvwxyz123456789ABCDEFGHJKMNPQRSTUVWXYZ')
    {
        // 注意这个key里面不能出现数字 0，否则当`求模=0`会重复的
        $this->key = $this->clear($key);
        // 多少进制
        $this->octal = strlen($this->key);
    }

    /**
     * @param  string  $str
     *
     * @return string
     */
    private function clear(string $str): string
    {
        $uniqueStr = trim(implode('', array_unique(str_split($str))));

        return str_replace('0', '', $uniqueStr);
    }

    /**
     * @param  float  $userId
     * @param  int  $length
     *
     * @return string
     */
    public function enCode(float $userId, $length = 8): string
    {
        $code = '';
        // 转进制
        while ($userId > 0) {
            $mod = $userId % $this->octal; // 求模

            $userId = ($userId - $mod) / $this->octal;

            $code = $this->key[$mod].$code;
        }

        return str_pad($code, $length, '0', STR_PAD_LEFT); // 不足用0补充;
    }

    /**
     * @param  string  $code
     *
     * @return float
     */
    public function deCode(string $code): float
    {
        if (strrpos($code, '0') !== false) {
            $code = substr($code, strrpos($code, '0') + 1);
        }

        $code = strrev($code);
        $len = strlen($code);
        $userId = 0;
        for ($i = 0; $i < $len; $i++) {
            $userId += strpos($this->key, $code[$i]) * ($this->octal ** $i);
        }

        return $userId;
    }
}
