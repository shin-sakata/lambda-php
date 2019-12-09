<?php

namespace Chemirea\Lambda\TypeClasses;

interface Applicative extends Functor
{
    public static function pure($x);

    public function apply($x);
}
