# lambda-php

[![Build Status](https://travis-ci.org/chemirea/lambda-php.svg?branch=master)](https://travis-ci.org/chemirea/lambda-php)

## About

関数志向をサポートするライブラリ

```php

<?php

use Chemirea\Lambda\Functional as F;

$add = function (int $x, int $y): int
{
    return $x + $y;
};

$wrappedAdd = F::wrap($add);

// この様に普通に呼び出せる
$wrappedAdd(1,2); // 3

// この様にカリー化されている
$wrappedAdd(1)(2); // 3

// 上と同じ
$addOne = $wrappedAdd(1);
$addOne(2); // 3


$addTwo = $wrappedAdd(2);

// この様に関数合成をして$addOneの返り値を,
// そのままaddTwoの引数として取る様な関数として定義することができる
$addOneTwo = $addOne->bind($addTwo);

$addOneTwo(1); // 4

```