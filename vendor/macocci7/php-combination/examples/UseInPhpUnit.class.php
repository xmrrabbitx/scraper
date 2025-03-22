<?php

namespace Macocci7\PhpCombination\Examples;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class UseInPhpUnit
{
    private Logger $log;

    public function __construct()
    {
        $this->log = new Logger('UseInPhpUnit');
        $this->log->pushHandler(
            new StreamHandler(__DIR__ . '/UseInPhpUnit.log', Level::Debug)
        );
    }

    public function order(
        int $productId,
        string $size,
        string $color,
        int $amount
    ) {
        $this->log->info('Adding a new order', [
            'productId' => $productId,
            'size' => $size,
            'color' => $color,
            'amount' => $amount,
        ]);
        return true;
    }
}
