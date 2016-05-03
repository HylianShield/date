<?php
/**
 * HylianShield Date Storage.
 */

namespace HylianShield\Date;

use DateTimeZone;

/**
 * Factory for date containers.
 *
 * @package HylianShield\Date
 */
class DateContainerFactory implements DateContainerFactoryInterface
{
    /**
     * @var DateTimeZone
     */
    private $timeZone;

    /**
     * DateContainerFactory constructor.
     *
     * @param null|DateTimeZone $timeZone
     */
    public function __construct(DateTimeZone $timeZone = null)
    {
        $this->timeZone = $timeZone ?: new DateTimeZone(date_default_timezone_get());
    }

    /**
     * Create a date container for the supplied format.
     *
     * @param string $format
     * @return DateContainerInterface
     */
    public function createFromFormat($format)
    {
        return new DateContainer(
            new DateStorage($format, $this->timeZone)
        );
    }

    /**
     * Create a container with an interval precision of seconds.
     *
     * @return DateContainerInterface
     */
    public function createIntervalSecond()
    {
        return $this->createFromFormat('Y-m-d H:i:s');
    }

    /**
     * Create a container with an interval precision of minutes.
     *
     * @return DateContainerInterface
     */
    public function createIntervalMinute()
    {
        return $this->createFromFormat('Y-m-d H:i');
    }

    /**
     * Create a container with an interval precision of hours.
     *
     * @return DateContainerInterface
     */
    public function createIntervalHour()
    {
        return $this->createFromFormat('Y-m-d H');
    }

    /**
     * Create a container with an interval precision of days.
     *
     * @return DateContainerInterface
     */
    public function createIntervalDay()
    {
        return $this->createFromFormat('Y-m-d');
    }
}
