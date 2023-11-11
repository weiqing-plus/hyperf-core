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

use JetBrains\PhpStorm\ArrayShape;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class Captcha
{
    protected CacheInterface $cache;

    private string $code; // 验证码

    private string $uniqId; // 唯一序号

    private string $charset = 'ABCDEFGHKMNPRSTUVWXYZ23456789'; // 随机因子

    private int $width = 130; // 图片宽度

    private int $height = 50; // 图片高度

    private int $length = 4; // 验证码长度

    private mixed $fontFile; // 指定字体文件

    private int $fontsize = 20; // 指定字体大小

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * 输出图形验证码
     * @return string
     */
    public function __toString()
    {
        return $this->getData();
    }

    public function init(array $config = []): static
    {
        // 动态配置属性
        foreach ($config as $k => $v) {
            if (isset($this->{$k})) {
                $this->{$k} = $v;
            }
        }
        // 生成验证码序号
        $this->uniqId = uniqid('captcha') . mt_rand(1000, 9999);
        // 生成验证码字符串
        [$this->code, $length] = ['', strlen($this->charset) - 1];
        for ($i = 0; $i < $this->length; ++$i) {
            $this->code .= $this->charset[mt_rand(0, $length)];
        }
        // 设置字体文件路径
        $this->fontFile = __DIR__ . '/bin/captcha.ttf';

        // 缓存验证码字符串
        $this->cache->set($this->uniqId, $this->code, 360);
        // 返回当前对象
        return $this;
    }

    /**
     * 获取验证码值
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * 获取图片内容.
     */
    public function getData(): string
    {
        return "data:image/png;base64,{$this->createImage()}";
    }

    /**
     * 获取验证码编号.
     */
    public function getUniqId(): string
    {
        return $this->uniqId;
    }

    /**
     * 获取验证码数据.
     */
    #[ArrayShape(['code' => 'string', 'data' => 'string', 'uniqId' => 'string'])]
    public function getAttrs(): array
    {
        return [
            'code' => $this->getCode(),
            'data' => $this->getData(),
            'uniqId' => $this->getUniqid(),
        ];
    }

    /**
     * 检查验证码是否正确.
     * @param string $code 需要验证的值
     * @param string|null $uniqId
     * @return bool
     * @throws InvalidArgumentException
     */
    public function check(string $code, ?string $uniqId = null): bool
    {
        $_val = $this->cache->get($uniqId, '');
        if (is_string($_val) && strtolower($_val) === strtolower($code)) {
            $this->cache->delete($uniqId);
            return true;
        }
        return false;
    }

    /**
     * 创建验证码图片.
     * @return string
     */
    private function createImage(): string
    {
        // 生成背景
        $img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($img, mt_rand(220, 255), mt_rand(220, 255), mt_rand(220, 255));
        imagefilledrectangle($img, 0, $this->height, $this->width, 0, $color);
        // 生成线条
        for ($i = 0; $i < 6; ++$i) {
            $color = imagecolorallocate($img, mt_rand(0, 50), mt_rand(0, 50), mt_rand(0, 50));
            imageline($img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        // 生成雪花
        for ($i = 0; $i < 100; ++$i) {
            $color = imagecolorallocate($img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
        // 生成文字
        $_x = $this->width / $this->length;
        for ($i = 0; $i < $this->length; ++$i) {
            $fontcolor = imagecolorallocate($img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            if (function_exists('imagettftext')) {
                imagettftext($img, $this->fontsize, mt_rand(-30, 30), intval($_x * $i + mt_rand(1, 5)), intval($this->height / 1.4), $fontcolor, $this->fontFile, $this->code[$i]);
            } else {
                imagestring($img, 15, intval($_x * $i + mt_rand(10, 15)), mt_rand(10, 30), $this->code[$i], $fontcolor);
            }
        }
        ob_start();
        imagepng($img);
        $data = ob_get_contents();
        ob_end_clean();
        imagedestroy($img);
        return base64_encode($data);
    }
}
