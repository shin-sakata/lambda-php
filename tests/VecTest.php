<?php

namespace Chemirea\Lambda\Tests;

use Chemirea\Lambda\Vec;
use PHPUnit\Framework\TestCase;

class VecTest extends TestCase
{
    public function testMap()
    {
        $nums = [0,1,2,3,4,5,6,7,8,9];

        $act = Vec::map(function($x) {
            return $x * 2;
        }, $nums);

        $this->assertEquals([0,2,4,6,8,10,12,14,16,18], $act);

        $mapTimesTwo = Vec::map(function($x) { return $x * 2; });

        $this->assertEquals([0,2,4,6,8,10,12,14,16,18], $mapTimesTwo($nums));
    }

    public function testReduce()
    {
        $nums = [1,2,3,4,5];

        $add = function ($x, $y)
        {
            return $x + $y;
        };

        $this->assertEquals(16, Vec::reduce($nums, $add, 1));
        $this->assertEquals(16, Vec::reduce($nums)($add, 1));

        $product = function ($carry, $item)
        {
            return $carry * $item;
        };

        $this->assertEquals(120, Vec::reduce($nums, $product, 1));
        $this->assertEquals(120, Vec::reduce($nums)($product, 1));
    }

    public function testFilterMap()
    {
        $nums = [1,2,3,4,5,6,7,8,9];

        $timesTwo = function($x)
        {
            return $x * 2;
        };

        $over5 = function($x)
        {
            return $x > 5;
        };

        $filterMap = Vec::filter()->Bind(Vec::map($timesTwo));

        $this->assertEquals([12,14,16,18], array_merge($filterMap($nums, $over5)));
    }
}