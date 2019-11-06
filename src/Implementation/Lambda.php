<?php

namespace Chemirea\Lambda;

use ArgumentCountError;
use ReflectionFunction;

class Lambda
{
    /**
     * 値かReflectionFunction.
     *
     * @var mixed | ReflectionFunction
     */
    private $item;

    /**
     * Functional constructor.
     * @param $x
     */
    private function __construct($x)
    {
        if (is_callable($x)) {
            try {
                $this->item = new ReflectionFunction($x);
            } catch (\ReflectionException $e) {
                throw new LambdaException($e->getMessage());
            }
        } else {
            $this->item = $x;
        }
    }

    /**
     * itemに対して引数を適用する.
     * 関数をカリー化させる.
     *
     * Apply arguments to item.
     * return currying function.
     *
     * @param mixed ...$args
     * @return $this
     */
    public function __invoke(...$args)
    {
        $f = $this->unwrap();

        if (count($args) < $f->getNumberOfRequiredParameters()) {
            return $this->partiallyApply($f, $args);
        }

        try {
            return $f->invokeArgs($args);
        } catch (ArgumentCountError $e) {
            return $this->partiallyApply($f, $args);
        }
    }

    /**
     * 部分適用.
     *
     * @param ReflectionFunction $f
     * @param $args
     * @return $this
     */
    private function partiallyApply(ReflectionFunction $f, $args)
    {
        return self::wrap(function (...$args2) use ($args, $f) {
            return $f->invokeArgs(array_merge($args, $args2));
        });
    }

    /**
     * Constructor alias.
     *
     * @param $x
     * @return static
     */
    public static function wrap($x): self
    {
        return new self($x);
    }

    /**
     * Return item.
     *
     * @return mixed
     */
    public function unwrap()
    {
        return $this->item;
    }

    /**
     * 関数合成をしてラッピングして返す.
     * itemが呼び出し可能でない場合は関数適用した上でラッピングして返す.
     *
     * Function composition.
     * If item is not a function, apply function to item.
     *
     * @param $f
     * @return $this
     */
    public function bind($f)
    {
        if (($this->unwrap() instanceof ReflectionFunction)) {
            return self::wrap(function (...$args) use ($f) {
                return $f($this(...$args));
            });
        } else {
            return self::wrap($this->apply($f));
        }
    }

    /**
     * itemを引数に適用させ、ラップせずに返す.
     *
     * Return applied item without wrapping.
     *
     * @param callable|null $f
     * @return mixed
     */
    public function apply(?callable $f = null)
    {
        return $f
            ? $f($this->unwrap())
            : $this->unwrap();
    }
}
