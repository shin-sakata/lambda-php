<?php

namespace Chemirea\Lambda;

use Chemirea\Lambda\TypeClasses\Monad;

class Maybe implements Monad
{
    /**
     * @var
     */
    private $value;

    public static function Nothing()
    {
        return self::pure(null);
    }

    public static function Just($value)
    {
        return self::pure($value);
    }

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function unwrap()
    {
        return $this->value;
    }

    public static function pure($value)
    {
        return new self($value);
    }

    public function fmap(callable $g): self
    {
        return ! is_null($this->unwrap())
            ? self::Just($g($this->unwrap()))
            : self::NOTHING();
    }

    public function apply($x): self
    {
        $f = $this->unwrap();

        return ! is_null($f)
            ? $x->fmap($f)
            : self::NOTHING();
    }

    public function bind(Lambda $g): self
    {
        $x = $this->unwrap();

        return ! is_null($x)
            ? $g($x)
            : self::NOTHING();
    }

    public function isJust(&$y): bool
    {
        $x = $this->unwrap();

        if (! is_null($x)) {
            $y = $x;

            return true;
        } else {
            return false;
        }
    }
}
