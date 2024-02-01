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

namespace Weiqing\HyperfCore\Traits;

use Hyperf\Database\Model\Builder;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;

trait ModelMacroTrait
{

    #[Inject]
    private RequestInterface $request;

    private function registerBase()
    {
        Builder::macro('getPageList', function ($perPage = null) {
            // 从request中获取分页参数
            $perPage ?: (int) $this->request->input('pageSize', 1);
            /* @var Builder $this */
            return $this->paginate($perPage);
        });
        Builder::macro('setSearch', function ($columns) {
            /* @var Builder $this */
            return $this;
        });
    }
}
