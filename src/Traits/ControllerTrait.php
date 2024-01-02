<?php

declare(strict_types=1);

namespace Weiqing\HyperfCore\Traits;
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\Contract\ValidatorInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\ValidationException;
use Hyperf\Validation\ValidatorFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Weiqing\HyperfCore\Response;
use Psr\Http\Message\ResponseInterface;

trait ControllerTrait
{
    #[Inject]
    protected ContainerInterface $container;

    #[Inject]
    protected RequestInterface $request;

    #[Inject]
    protected Response $response;


    public function success(array|object|string $msgOrData = '', array|object $data = [], int $code = 200): ResponseInterface
    {
        if (is_string($msgOrData) || is_null($msgOrData)) {
            return $this->response->success($msgOrData, $data, $code);
        }
        if (is_array($msgOrData) || is_object($msgOrData)) {
            return $this->response->success(null, $msgOrData, $code);
        }
        return $this->response->success(null, $data, $code);
    }


    public function error(string $message = '', int $code = 500, array $data = []): ResponseInterface
    {
        return $this->response->error($message, $code, $data);
    }

    /**
     * 跳转.
     */
    public function redirect(string $toUrl, int $status = 302, string $schema = 'http'): ResponseInterface
    {
        return $this->response->redirect($toUrl, $status, $schema);
    }

    /**
     * 下载文件.
     */
    public function _download(string $filePath, string $name = ''): ResponseInterface
    {
        return $this->response->download($filePath, $name);
    }

    /**
     * 验证参数.
     */
    public function validate(array $rules, array $messages = [], array $customAttributes = []): array
    {

        /* @var ValidatorInterface $validator */
        $validator = $this->container->get(ValidatorFactory::class)->make(
            $this->request->all(),
            $rules,
            $messages,
            $customAttributes
        );
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return $this->request->all();
    }
}
