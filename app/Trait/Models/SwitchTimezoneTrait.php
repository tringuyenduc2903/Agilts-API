<?php

namespace App\Trait\Models;

use DateTimeInterface;

trait SwitchTimezoneTrait
{
    /**
     * @param DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date): string
    {
        $timezone = auth()->check()
            ? auth()->user()->timezone
            : config('app.timezone');

        return $date->timezone($timezone)->format(config('app.timezone-format.long'));
    }
}
