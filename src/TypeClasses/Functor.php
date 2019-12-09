<?php

namespace Chemirea\Lambda\TypeClasses;

use Chemirea\Lambda\Lambda;

interface Functor
{
    public function fmap(callable $g);
}
