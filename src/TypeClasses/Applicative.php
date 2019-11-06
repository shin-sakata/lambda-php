<?php

namespace Chemirea\Lambda\TypeClasses;

interface Applicative extends Functor
{
    public function pure($x);

    public function seqApply($x);

    public function apply($x);
}
