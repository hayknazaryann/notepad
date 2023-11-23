<?php

namespace App\Enums;

enum PageSizes
{
    public const LIMIT_10 = 10;
    public const LIMIT_25 = 25;
    public const LIMIT_50 = 50;

    /**
     * @return int[]
     */
    public static function all(): array
    {
        return [
            self::LIMIT_10, self::LIMIT_25, self::LIMIT_50
        ];
    }
}
