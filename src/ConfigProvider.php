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

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'middlewares' => [
                'http' => [
                    \Hyperf\Validation\Middleware\ValidationMiddleware::class,
                ],
            ],
            'exceptions' => [
                'handler' => [
                    'http' => [
                        \Weiqing\HyperfCore\Exception\Handler\ValidationExceptionHandler::class,
                        \Weiqing\HyperfCore\Exception\Handler\TokenExceptionHandler::class,
                    ],
                ],
            ],
        ];
    }
}
