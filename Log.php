<?php
/**
 * Created by PhpStorm.
 * User: gaoli
 * Date: 2020/10/6
 * Time: 下午6:33
 */

namespace app\libs\DingDing\crypto;

use Lib\Config\Conf;


class Log
{
    public static function i($msg)
    {
        self::write('I', $msg);
    }

    public static function e($msg)
    {
        self::write('E', $msg);
    }

    private static function write($level, $msg)
    {
        $filename = Conf::LOGS_PATH . '/' . "isv.log";
        $logFile = fopen($filename, "aw");
        fwrite($logFile, $level . "/" . date(" Y-m-d h:i:s") . "  " . $msg . "\n");
        fclose($logFile);
    }

}