<?php

namespace Chemirea\Lambda\TypeClasses;

interface Applicative extends Functor
{
    static public function pure($x);

    public function apply($x);
}
