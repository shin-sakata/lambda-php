# lambda-php

[![Build Status](https://travis-ci.org/chemirea/lambda-php.svg?branch=master)](https://travis-ci.org/chemirea/lambda-php)
[![codecov](https://codecov.io/gh/chemirea/lambda-php/branch/master/graph/badge.svg)](https://codecov.io/gh/chemirea/lambda-php)
[![StyleCI](https://github.styleci.io/repos/217989836/shield?branch=master)](https://github.styleci.io/repos/217989836)
[![Maintainability](https://api.codeclimate.com/v1/badges/91fb9473212123f50f80/maintainability)](https://codeclimate.com/github/chemirea/lambda-php/maintainability)
[![MIT License](http://img.shields.io/badge/license-MIT-blue.svg?style=flat)](LICENSE)

## About

関数志向をサポートするライブラリ

```php

<?php

use Chemirea\Lambda\Lambda as L;

$add = function (int $x, int $y): int {
    return $x + $y;
};

$wrappedAdd = L::wrap($add);

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

## install

```
$ composer require chemirea/lambda-php=dev-master
```
