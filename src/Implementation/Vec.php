<?php

namespace Chemirea\Lambda;

use Chemirea\Lambda\Lambda as L;

trait Vec
{
    public static function __callStatic($name, $args)
    {
        $array_function = 'array_'.$name;

        return function_exists($array_function)
            ? L::wrap($array_function)(...$args)
            : L::wrap($name)(...$args);
    }
}
