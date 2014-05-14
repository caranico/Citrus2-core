<?php

namespace Citrus\Core\System;

interface ServiceContainerInterface
{

    public function registerProviders();

    public function registerProvider(ServiceProviderInterface $provider);
}
