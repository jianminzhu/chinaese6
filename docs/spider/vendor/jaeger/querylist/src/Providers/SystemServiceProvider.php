<?php
/**
 * Created by PhpStorm.
 * User: Jaeger <JaegerCode@gmail.com>
 * Date: 2017/9/22
 */

namespace QL\Providers;

use Closure;
use QL\Contracts\ServiceProviderContract;
use QL\Kernel;

class SystemServiceProvider implements ServiceProviderContract
{
    public function register(Kernel $kernel)
    {
        $kernel->bind('html',function (...$args){
            $this->setHtml(...$args);
            return $this;
        });

        $kernel->bind('queryData',function (Closure $callback = null){
            return $this->query()->getData($callback)->all();
        });

        $kernel->bind('pipe',function (Closure $callback = null){
            return $callback($this);
        });

    }
}