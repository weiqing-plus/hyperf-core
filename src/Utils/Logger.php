<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Weiqing\HyperfCore\Utils;

use Exception;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\StdoutLoggerInterface;

class Logger
{
    public static function write($message)
    {
        $logPath = BASE_PATH . '/runtime/logs/';
        if (! is_dir($logPath)) {
            mkdir($logPath, 0777, true);
        }
        // 把日志写入到文件
        $file = $logPath . date('Y-m-d') . '.log';
        $message = sprintf(
            '[%s] %s',
            date('Y-m-d H:i:s'),
            $message
        );
        file_put_contents($file, $message . PHP_EOL, FILE_APPEND);
    }

    /**
     * 获取控制台日志实例.
     * @return mixed|StdoutLoggerInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function console()
    {
        return ApplicationContext::getContainer()->get(StdoutLoggerInterface::class);
    }

    /**
     * 输出错误日志.
     * @param mixed $message
     */
    public static function error($message, array $context = [])
    {
        try {
            // 获取调用此方法的类名
            $class = debug_backtrace()[1]['class'];
            // 获取调用此方法的方法名
            $method = debug_backtrace()[1]['function'];
            // 获取调用此方法的行数
            $line = debug_backtrace()[1]['line'];
            // 获取调用此方法的命名空间
            $namespace = explode('\\', $class);
            $timeZone = date_default_timezone_get();
            // 临时修改时区
            date_default_timezone_set('PRC');
            $date = date('Y-m-d H:i:s');
            date_default_timezone_set($timeZone);
            $message = sprintf(
                '[%s][%s] %s:%s %s',
                $date,
                end($namespace),
                $method,
                $line,
                $message
            );
            self::console()->error($message, $context);
        } catch (Exception $e) {
            self::console()->error($message, $context);
        }
    }
}
