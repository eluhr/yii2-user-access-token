<?php

namespace eluhr\userAuthToken\helpers;

use DateTimeImmutable;

class DateHelper
{
    /**
     * Get the current date time in the format Y-m-d H:i:s
     *
     * @return string
     */
    public static function now(): string
    {
        return (new DateTimeImmutable())->format('Y-m-d H:i:s');
    }
}