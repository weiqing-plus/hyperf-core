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

trait ModelMacroTrait
{
    private function registerBase()
    {
        Builder::macro('getPageList', function ($perPage = 15) {
            /* @var Builder $this */
            return $this->paginate($perPage);
        });
    }
}
