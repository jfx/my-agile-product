<?php

namespace Map3\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Map3UserBundle extends Bundle
{
    /**
     * Returns the bundle parent name.
     *
     * @return string The Bundle parent name it overrides.
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
