<?php

declare(strict_types=1);

namespace App\Utilities;

use Carbon\Carbon;

class TimezoneHelper
{
    /**
     * Convert a datetime from a specific timezone to UTC
     */
    public static function toUTC(string $datetime, string $timezone): string
    {
        return Carbon::parse($datetime, $timezone)
            ->utc()
            ->format('Y-m-d H:i:s');
    }

    /**
     * Convert a UTC datetime to a specific timezone
     */
    public static function fromUTC(string $datetime, string $timezone): string
    {
        return Carbon::parse($datetime, 'UTC')
            ->setTimezone($timezone)
            ->format('Y-m-d H:i:s');
    }

    /**
     * Get the current datetime in a specific timezone
     */
    public static function nowInTimezone(string $timezone): string
    {
        return Carbon::now($timezone)->format('Y-m-d H:i:s');
    }

    /**
     * Validate if a timezone is valid
     */
    public static function isValidTimezone(string $timezone): bool
    {
        return in_array($timezone, timezone_identifiers_list());
    }

    /**
     * Get common timezones for Mexico
     */
    public static function getCommonTimezones(): array
    {
        return [
            'America/Puerto_Rico' => 'Puerto Rico (UTC-4)',
        ];
    }
}
