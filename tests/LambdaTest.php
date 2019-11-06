<?php

namespace Chemirea\Lambda\Tests;

use PHPUnit\Framework\TestCase;
use Chemirea\Lambda\Lambda as L;

class LambdaTest extends TestCase
{
    public function testInvoke()
    {
        $addOne = function (int $x): int {
            return $x + 1;
        };

        $wrappedAddOne = L::wrap($addOne);

        $this->assertEquals(2, $wrappedAddOne(1));
    }

    public function testSomeArgsInvoke()
    {
        $add = function (int $x, int $y): int {
            return $x + $y;
        };

        $wrappedAdd = L::wrap($add);

        $this->assertEquals(3, $wrappedAdd(1, 2));

        $concat = L::wrap(function (string $str1, string $str2): string {
            return $str1.$str2;
        });

        $this->assertEquals('Hello world!', $concat('Hello ')('world!'));
    }

    public function testBind()
    {
        $add = function (int $x, int $y): int {
            return $x + $y;
        };

        $addOne = function (int $x): int {
            return $x + 1;
        };

        $add_addOne = L::wrap($add)->bind($addOne);

        $this->assertEquals(4, $add_addOne(1, 2));
    }

    public function testBindBind()
    {
        $add = function (int $x, int $y): int {
            return $x + $y;
        };

        $addOne = function (int $x): int {
            return $x + 1;
        };

        $add_addOne = L::wrap($add)->bind($addOne)->bind($addOne);

        $this->assertEquals(5, $add_addOne(1, 2));
    }

    public function testApplyFunction()
    {
        $addOne = function (int $x): int {
            return $x + 1;
        };

        $this->assertEquals(2, L::wrap(1)->apply($addOne));
    }

    public function testApplyBindingFunction()
    {
        $addOne = function (int $x): int {
            return $x + 1;
        };

        $this->assertEquals(4,
            L::wrap(1)
                ->bind($addOne)
                ->bind($addOne)
                ->apply($addOne)
        );

        $this->assertEquals(4,
            L::wrap(1)
                ->bind($addOne)
                ->bind($addOne)
                ->bind($addOne)
                ->apply()
        );
    }

    public function testCurry()
    {
        $add = L::wrap(function (int $x, int $y): int {
            return $x + $y;
        });

        $div = L::wrap(function (int $x, int $y): int {
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
        $addAdd = L::wrap(function (int $x, int $y, int $z): int {
            return $x + $y + $z;
        });

        $this->assertEquals(1 + 2 + 3, $addAdd(1)(2)(3));
        $this->assertEquals(1 + 2 + 3, $addAdd(1, 2)(3));
        $this->assertEquals(1 + 2 + 3, $addAdd(1)(2, 3));

        $addAddAdd = L::wrap(function (int $w, int $x, int $y, int $z): int {
            return $w + $x + $y + $z;
        });

        $this->assertEquals(
            1 + 2 + 3 + 4,
            $addAddAdd(1)(2)(3)(4));
    }

    public function testBindCurriedFunction()
    {
        $add = L::wrap(function (int $x, int $y): int {
            return $x + $y;
        });

        $div = L::wrap(function (int $x, int $y): int {
            return $y / $x;
        });

        $addOne = $add(1);
        $divByTwo = $div(2);

        $divTwoAddOne = $divByTwo->bind($addOne);

        $this->assertEquals(6, $divTwoAddOne(10));
    }

    public function testApplyBindedCurriedFunction()
    {
        $add = L::wrap(function (int $x, int $y): int {
            return $x + $y;
        });

        $div = L::wrap(function (int $x, int $y): int {
            return $y / $x;
        });

        $addOne = $add(1);
        $divByTwo = $div(2);

        $divTwoAddOne = $divByTwo->bind($addOne);

        $this->assertEquals(6, L::wrap(10)->apply($divTwoAddOne));
        $this->assertEquals(6, L::wrap(10)->bind($divTwoAddOne)->apply());
    }

    /**
     * 型が違う場合に正しくエラーがスローされるかのテスト1.
     */
    public function testTypeErrorException1()
    {
        $add = L::wrap(function (int $x, int $y): int {
            return $x + $y;
        });

        $this->expectException(\TypeError::class);
        $add('one')(1);
    }

    /**
     * 型が違う場合に正しくエラーがスローされるかのテスト2.
     */
    public function testTypeErrorException2()
    {
        $add = L::wrap(function (int $x, int $y): int {
            return $x + $y;
        });

        $this->expectException(\TypeError::class);
        $add(1)('one');
    }
}
