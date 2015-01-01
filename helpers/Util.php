<?php 

namespace app\helpers;

class Util
{
    /**
     * Convert TZ.
     *
     * @param string $date
     * @param string $fromTimeZone
     * @param string $toTimeZone
     * @param string $format
     * @return string
     */
    public static function convertTz($date, $fromTimeZone, $toTimeZone, $format = 'Y-m-d H:i:s')
    {
        $date = new \DateTime($date, new \DateTimeZone($fromTimeZone));
        $date->setTimeZone(new \DateTimeZone($toTimeZone));
        
        return $date->format($format);
    }
}
