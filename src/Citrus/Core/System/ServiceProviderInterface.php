<?php
namespace Citrus\Core\System;

interface ServiceProviderInterface
{
    public function register($app);

    public function boot($app);
}
