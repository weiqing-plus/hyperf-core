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

namespace Weiqing\HyperfCore;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Weiqing\HyperfCore\Log\RequestIdHolder;
use Psr\Http\Message\ResponseInterface;

class Response extends \Hyperf\HttpServer\Response
{
    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function success(string $message = null, array|object $data = [], int $code = 200): ResponseInterface
    {
        $format = [
            'requestId' => RequestIdHolder::getId(),
            'success' => true,
            'message' => $message ?: 'SUCCESS',
            'code' => $code,
            'data' => &$data,
        ];
        $format = $this->toJson($format);
        return $this->getResponse()
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody(new SwooleStream($format));
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function error(string $message = '', int $code = 500, array $data = []): ResponseInterface
    {
        $format = [
            'requestId' => RequestIdHolder::getId(),
            'success' => false,
            'code' => $code,
            'message' => $message ?: 'ERROR',
        ];

        if (! empty($data)) {
            $format['data'] = &$data;
        }

        $format = $this->toJson($format);
        return $this->getResponse()
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody(new SwooleStream($format));
    }

    /**
     * 向浏览器输出图片.
     */
    public function responseImage(string $image, string $type = 'image/png'): ResponseInterface
    {
        return $this->getResponse()
            ->withAddedHeader('content-type', $type)
            ->withBody(new SwooleStream($image));
    }

    public function getResponse(): ResponseInterface
    {
        return parent::getResponse();
    }
}
