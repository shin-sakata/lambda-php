<?php

namespace Chemirea\Lambda\Tests;

use PHPUnit\Framework\TestCase;
use Chemirea\Lambda\Lambda as L;
use Chemirea\Lambda\Maybe;

class MaybeTest extends TestCase
{
    public function testFmap()
    {
        $maybe1 = Maybe::Just(1);

        $wrappedAdd5 = L::wrap(function ($x) {
            return $x + 5;
        });

        $add5 = function ($x) { return $x + 5; };

        $this->assertEquals(6, $maybe1->fmap($wrappedAdd5)->unwrap());
        $this->assertEquals(6, $maybe1->fmap($add5)->unwrap());
    }

    public function testNothingFmap()
    {
        $nothing = Maybe::Nothing();

        $wrappedAdd5 = L::wrap(function ($x) {
            return $x + 5;
        });

        $add5 = function ($x) { return $x + 5; };

        $this->assertEquals(Maybe::Nothing() , $nothing->fmap($wrappedAdd5));
        $this->assertEquals(Maybe::Nothing() , $nothing->fmap($add5));
        $this->assertEquals(Maybe::Nothing() , $nothing->fmap($wrappedAdd5)->fmap($add5));
    }

    public function testApply()
    {
        $maybe1 = Maybe::Just(1);
        $add = L::wrap(function ($x, $y) { return $x + $y; });
        $maybeAdd5 = Maybe::Just($add(5));
        $this->assertEquals(6, $maybeAdd5->apply($maybe1)->unwrap());


        $maybeAdd = Maybe::Just($add);
        $maybe2 = Maybe::Just(2);
        $this->assertEquals(3, $maybeAdd->apply($maybe1)->apply($maybe2)->unwrap());

        $maybeAdd1 = Maybe::Just($add(1));
        $this->assertEquals(2, $maybeAdd1->apply($maybe1)->unwrap());
    }

    public function testBind()
    {
        $safeDivBy = L::wrap(function($x, $y) {
            return $x === 0
                ? Maybe::Nothing()
                : Maybe::Just( $y / $x);
        });

        $maybe100 = Maybe::pure(100);

        $this->assertEquals(
            25,
            $maybe100
                ->bind($safeDivBy(2))
                ->bind($safeDivBy(2))
                ->unwrap());

        $this->assertEquals(
            Maybe::Nothing(),
            $maybe100
                ->bind($safeDivBy(2))
                ->bind($safeDivBy(0))
        );

        $this->assertEquals(
            Maybe::Nothing(),
            $maybe100
                ->bind($safeDivBy(0))
                ->bind($safeDivBy(2))
        );
    }

    public function testPattern()
    {
        $maybeOne = Maybe::Just(1);

        $nothing = Maybe::Nothing();

        $this->assertEquals(true, $maybeOne->isJust($y));
        $this->assertEquals(1, $y);

        $this->assertEquals(false, $nothing->isJust($z));
        $this->assertEquals(false, !is_null($z));
    }

    public function testMatch()
    {
        $maybeOne = Maybe::Just(1);

        if ($maybeOne->isJust($x)) {
            $this->assertEquals(1, $x);
        }

        $maybeNothing = Maybe::Nothing();
        if ($maybeNothing->isJust($y)) {
            // 来ちゃいけない
            $this->assertTrue(false);
        } else {
            $this->assertTrue(true);
        }
    }
}
