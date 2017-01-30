<?php
namespace HylianShield\Date\Tests;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use PHPUnit_Framework_TestCase;

abstract class AbstractDateTimeTestCase extends PHPUnit_Framework_TestCase
{
    const FORMAT            = DateTime::RFC3339;
    const EXPECTED_TIMEZONE = 'europe/amsterdam';
    const SUPPLIED_TIMEZONE = 'europe/berlin';

    /**
     * @param string|null $name
     *
     * @return DateTimeZone
     */
    protected function createTimeZone(string $name = null): DateTimeZone
    {
        $timeZone = $this->createMock(DateTimeZone::class);

        if ($name !== null) {
            $timeZone
                ->expects($this->atLeastOnce())
                ->method('getName')
                ->willReturn($name);
        }

        return $timeZone;
    }

    /**
     * @param DateTimeZone|null $timeZone
     * @param int|null          $timeStamp
     *
     * @return DateTimeInterface
     */
    protected function createDateTime(
        DateTimeZone $timeZone = null,
        int $timeStamp = null
    ): DateTimeInterface {
        $dateTime = $this->createMock(DateTimeImmutable::class);

        if ($timeZone !== null) {
            $dateTime
                ->expects($this->any())
                ->method('getTimezone')
                ->willReturn($timeZone);
        }

        if ($timeStamp !== null) {
            $dateTime
                ->expects($this->any())
                ->method('format')
                ->with($this->isType('string'))
                ->willReturnCallback(
                    function (string $format) use ($timeStamp) : string {
                        return date($format, $timeStamp);
                    }
                );
        }

        return $dateTime;
    }
}
