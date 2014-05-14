<?php

namespace Citrus\Core\Controller;

interface ControllerInterface
{

    public function render($args, $view = null);
}
