<?php

namespace Weiqing\HyperfCore\Exception\Handler;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Weiqing\HyperfCore\Exception\TokenException;
use Weiqing\HyperfCore\Traits\ControllerTrait;

class TokenExceptionHandler extends ExceptionHandler
{

    use ControllerTrait;

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();
        return $this->error($throwable->getMessage(), $throwable->getCode());
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof TokenException;
    }
}
