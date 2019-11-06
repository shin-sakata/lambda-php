<?php

namespace Chemirea\Lambda\TypeClasses;

interface Monad extends Applicative
{
    public function bind($x);
}
