<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Chronos\ChronosDate;
use Cake\View\Helper\TimeHelper as CakeTimeHelper;
use DateTimeInterface;
use DateTimeZone;

class TimeHelper extends CakeTimeHelper
{
    protected function coerce(ChronosDate|DateTimeInterface|string|int|null $date): ChronosDate|DateTimeInterface|string|int
    {
        if ($date === null || $date === '') {
            return '9999-12-31 00:00:00';
        }

        return $date;
    }

    public function isFuture(
        ChronosDate|DateTimeInterface|string|int|null $dateString,
        DateTimeZone|string|null $timezone = null,
    ): bool {
        return parent::isFuture($this->coerce($dateString), $timezone);
    }

    public function timeAgoInWords(
        ChronosDate|DateTimeInterface|string|int|null $dateTime,
        array $options = [],
    ): string {
        if ($dateTime === null || $dateTime === '') {
            return '';
        }

        return parent::timeAgoInWords($dateTime, $options);
    }
}
