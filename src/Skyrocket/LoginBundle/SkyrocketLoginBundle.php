<?php

namespace Skyrocket\LoginBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SkyrocketLoginBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
