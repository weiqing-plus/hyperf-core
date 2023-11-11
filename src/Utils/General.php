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

class General
{
    /**
     * 获取随机字符串编码
     * @param int $size 编码长度
     * @param int $type 编码类型(1纯数字,2纯字母,3数字字母)
     * @param string $prefix 编码前缀
     */
    public static function random(int $size = 10, int $type = 1, string $prefix = ''): string
    {
        $numbs = '0123456789';
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        if ($type === 1) {
            $chars = $numbs;
        }
        if ($type === 3) {
            $chars = "{$numbs}{$chars}";
        }
        $code = $prefix . $chars[rand(1, strlen($chars) - 1)];
        while (strlen($code) < $size) {
            $code .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $code;
    }

    /**
     * 加密密码
     */
    public static function saltMd5Encrypt(string $string, string $salt): string
    {
        $tmp = md5($string) . $salt;
        return md5(md5($tmp) . md5($salt));
    }

    /**
     * 数组转树形结构.
     * @param mixed $arr
     */
    public static function arr2Tree(mixed $arr, int $pid = 0, int $depth = 0, string $p_sub = 'parent_id', string $c_sub = 'children', string $d_sub = 'depth'): array
    {
        $returnArray = [];
        if (is_array($arr) && $arr) {
            foreach ($arr as $k => $v) {
                if ($v[$p_sub] == $pid) {
                    $v[$d_sub] = $depth;
                    $tempInfo = $v;
                    unset($arr[$k]); // 减少数组长度，提高递归的效率，否则数组很大时肯定会变慢
                    $temp = self::arr2Tree($arr, $v['id'], $depth + 1, $p_sub, $c_sub, $d_sub);
                    if ($temp) {
                        $tempInfo[$c_sub] = $temp;
                    }
                    $returnArray[] = $tempInfo;
                }
            }
        }
        return $returnArray;
    }

    /**
     * 获取唯一ID.
     * @return string
     */
    public static function uuid(): string
    {
        $uuid = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xFFFF),
            mt_rand(0, 0xFFFF),
            mt_rand(0, 0xFFFF),
            mt_rand(0, 0x0FFF) | 0x4000,
            mt_rand(0, 0x3FFF) | 0x8000,
            mt_rand(0, 0xFFFF),
            mt_rand(0, 0xFFFF),
            mt_rand(0, 0xFFFF)
        );
        return strtoupper($uuid);
    }
}
