<?php
namespace HylianShield\Date;

interface DateContainerFactoryInterface
{
    /**
     * Create a date container for the supplied format.
     *
     * @param string $format
     *
     * @return DateContainerInterface
     */
    public function createFromFormat(string $format): DateContainerInterface;

    /**
     * Create a container with an interval precision of seconds.
     *
     * @return DateContainerInterface
     */
    public function createIntervalSecond(): DateContainerInterface;

    /**
     * Create a container with an interval precision of minutes.
     *
     * @return DateContainerInterface
     */
    public function createIntervalMinute(): DateContainerInterface;

    /**
     * Create a container with an interval precision of hours.
     *
     * @return DateContainerInterface
     */
    public function createIntervalHour(): DateContainerInterface;

    /**
     * Create a container with an interval precision of days.
     *
     * @return DateContainerInterface
     */
    public function createIntervalDay(): DateContainerInterface;
}
