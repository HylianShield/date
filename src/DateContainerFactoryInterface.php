<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */
namespace HylianShield\Date;

/**
 * Factory for date containers.
 *
 * @package HylianShield\Date
 */
interface DateContainerFactoryInterface
{
    /**
     * Create a date container for the supplied format.
     *
     * @param string $format
     * @return DateContainerInterface
     */
    public function createFromFormat($format);

    /**
     * Create a container with an interval precision of seconds.
     *
     * @return DateContainerInterface
     */
    public function createIntervalSecond();

    /**
     * Create a container with an interval precision of minutes.
     *
     * @return DateContainerInterface
     */
    public function createIntervalMinute();

    /**
     * Create a container with an interval precision of hours.
     *
     * @return DateContainerInterface
     */
    public function createIntervalHour();

    /**
     * Create a container with an interval precision of days.
     *
     * @return DateContainerInterface
     */
    public function createIntervalDay();
}
