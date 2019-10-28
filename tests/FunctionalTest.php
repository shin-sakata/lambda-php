<?php

namespace Chemirea\Lambda\Tests;

use Chemirea\Lambda\Functional as F;
use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    public function testInvoke()
    {
        $addOne = function(int $x): int
        {
            return $x + 1;
        };

        $wrappedAddOne = F::wrap($addOne);

        $this->assertEquals(2, $wrappedAddOne(1));
    }

    public function testSomeArgsInvoke()
    {
        $add = function (int $x, int $y): int
        {
            return $x + $y;
        };

        $wrappedAdd = F::wrap($add);

        $this->assertEquals(3, $wrappedAdd(1, 2));

        $concat = F::wrap(function (string $str1, string $str2): string
        {
            return $str1 . $str2;
        });

        $this->assertEquals("Hello world!", $concat("Hello ")("world!"));
    }

    public function testBind()
    {
        $add = function (int $x, int $y): int
        {
            return $x + $y;
        };

        $addOne = function (int $x): int
        {
            return $x + 1;
        };

        $add_addOne = F::wrap($add)->bind($addOne);

        $this->assertEquals(4, $add_addOne(1, 2));
    }

    public function testBindBind()
    {
        $add = function (int $x, int $y): int
        {
            return $x + $y;
        };

        $addOne = function(int $x): int
        {
            return $x + 1;
        };

        $add_addOne = F::wrap($add)->bind($addOne)->bind($addOne);

        $this->assertEquals(5, $add_addOne(1, 2));
    }

    public function testApplyFunction()
    {
        $addOne = function(int $x): int
        {
            return $x + 1;
        };

        $this->assertEquals(2, F::wrap(1)->apply($addOne));
    }

    public function testApplyBindingFunction()
    {
        $addOne = function(int $x): int
        {
            return $x + 1;
        };

        $this->assertEquals(4,
            F::wrap(1)
                ->bind($addOne)
                ->bind($addOne)
                ->apply($addOne)
        );

        $this->assertEquals(4,
            F::wrap(1)
                ->bind($addOne)
                ->bind($addOne)
                ->bind($addOne)
                ->apply()
        );
    }

    public function testCurry()
    {
        $add = F::wrap(function(int $x, int $y): int
        {
            return $x + $y;
        });

        $div = F::wrap(function(int $x, int $y): int
        {
            return $y / $x;
        });

        $this->assertEquals(3, $add(1, 2));
        $this->assertEquals(5, $div(2, 10));
        $this->assertEquals(3, $add(1)(2));
        $this->assertEquals(5, $div(2)(10));

        $addOne = $add(1);
        $addTwo = $add(2);

        $addOneTwo = $addOne->bind($addTwo);

        $this->assertEquals(4, $addOneTwo(1));
    }

    public function testManyCurry()
    {
        $addAdd = F::wrap(function(int $x, int $y, int $z): int
        {
            return $x + $y + $z;
        });

        $this->assertEquals(1 + 2 + 3, $addAdd(1)(2)(3));
        $this->assertEquals(1 + 2 + 3, $addAdd(1,2)(3));
        $this->assertEquals(1 + 2 + 3, $addAdd(1)(2, 3));

        $addAddAdd = F::wrap(function(int $w, int $x, int $y, int $z): int
        {
            return $w + $x + $y + $z;
        });

        $this->assertEquals(
            1 + 2 + 3 + 4,
            $addAddAdd(1)(2)(3)(4));
    }

    public function testBindCurriedFunction()
    {
        $add = F::wrap(function(int $x, int $y): int
        {
            return $x + $y;
        });

        $div = F::wrap(function(int $x, int $y): int
        {
            return $y / $x;
        });

        $addOne = $add(1);
        $divByTwo = $div(2);

        $divTwoAddOne = $divByTwo->bind($addOne);

        $this->assertEquals(6, $divTwoAddOne(10));
    }

    public function testApplyBindedCurriedFunction()
    {
        $add = F::wrap(function(int $x, int $y): int
        {
            return $x + $y;
        });

        $div = F::wrap(function(int $x, int $y): int
        {
            return $y / $x;
        });

        $addOne = $add(1);
        $divByTwo = $div(2);

        $divTwoAddOne = $divByTwo->bind($addOne);

        $this->assertEquals(6, F::wrap(10)->apply($divTwoAddOne));
        $this->assertEquals(6, F::wrap(10)->bind($divTwoAddOne)->apply());
    }

    /**
     * 型が違う場合に正しくエラーがスローされるかのテスト1
     */
    public function testTypeErrorException1()
    {
        $add = F::wrap(function(int $x, int $y): int
        {
            return $x + $y;
        });

        $this->expectException(\TypeError::class);
        $add('one')(1);
    }

    /**
     * 型が違う場合に正しくエラーがスローされるかのテスト2
     */
    public function testTypeErrorException2()
    {
        $add = F::wrap(function(int $x, int $y): int
        {
            return $x + $y;
        });

        $this->expectException(\TypeError::class);
        $add(1)('one');
    }
}
