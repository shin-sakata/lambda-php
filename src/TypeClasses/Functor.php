<?php

namespace Chemirea\Lambda\TypeClasses;

interface Functor
{
    public function fmap($g);
}
