<?php

namespace app\helpers;

use DateTime;
use DateTimeZone;

class I18n
{
    /**
     * Convert TZ
     *
     * @param string $date
     * @param string $fromTimeZone
     * @param string $toTimeZone
     * @return DateTime
     */
    public static function convertTz($date, $fromTimeZone, $toTimeZone)
    {
        $date = new DateTime($date, new DateTimeZone($fromTimeZone));
        $date->setTimeZone(new DateTimeZone($toTimeZone));

        return $date;
    }
}
