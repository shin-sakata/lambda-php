<?php

namespace Chemirea\Lambda;

use Chemirea\Lambda\Functional as F;

trait Vec
{
    public static function __callStatic($name, $args)
    {
        $array_function = 'array_'.$name;

        return function_exists($array_function)
            ? F::wrap($array_function)(...$args)
            : F::wrap($name)(...$args);
    }
}
