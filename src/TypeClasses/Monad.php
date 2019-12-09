<?php

namespace Chemirea\Lambda\TypeClasses;

use Chemirea\Lambda\Lambda;

interface Monad extends Applicative
{
    public function bind(Lambda $x);
}
