<?php
namespace HylianShield\Date\Tests;

use HylianShield\Date\DateContainerFactory;
use HylianShield\Date\DateContainerInterface;

/**
 * @coversDefaultClass \HylianShield\Date\DateContainerFactory
 */
class DateContainerFactoryTest extends AbstractDateTimeTestCase
{
    /**
     * @return DateContainerFactory
     * @covers ::__construct
     */
    public function testConstructor(): DateContainerFactory
    {
        return new DateContainerFactory();
    }

    /**
     * @depends testConstructor
     *
     * @param DateContainerFactory $factory
     *
     * @return DateContainerInterface
     * @covers ::createFromFormat
     */
    public function testCreateFromFormat(
        DateContainerFactory $factory
    ): DateContainerInterface {
        return $factory->createFromFormat(static::FORMAT);
    }

    /**
     * @depends testConstructor
     *
     * @param DateContainerFactory $factory
     *
     * @return DateContainerInterface
     * @covers ::createIntervalSecond
     */
    public function testCreateIntervalSecond(
        DateContainerFactory $factory
    ): DateContainerInterface {
        return $factory->createIntervalSecond();
    }

    /**
     * @depends testConstructor
     *
     * @param DateContainerFactory $factory
     *
     * @return DateContainerInterface
     * @covers ::createIntervalMinute
     */
    public function testCreateIntervalMinute(
        DateContainerFactory $factory
    ): DateContainerInterface {
        return $factory->createIntervalMinute();
    }

    /**
     * @depends testConstructor
     *
     * @param DateContainerFactory $factory
     *
     * @return DateContainerInterface
     * @covers ::createIntervalHour
     */
    public function testCreateIntervalHour(
        DateContainerFactory $factory
    ): DateContainerInterface {
        return $factory->createIntervalHour();
    }

    /**
     * @depends testConstructor
     *
     * @param DateContainerFactory $factory
     *
     * @return DateContainerInterface
     * @covers ::createIntervalDay
     */
    public function testCreateIntervalDay(
        DateContainerFactory $factory
    ): DateContainerInterface {
        return $factory->createIntervalDay();
    }
}
