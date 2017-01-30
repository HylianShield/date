<?php
namespace HylianShield\Date\Tests;

use HylianShield\Date\DateStorage;
use stdClass;

/**
 * @coversDefaultClass \HylianShield\Date\DateStorage
 */
class DateStorageTest extends AbstractDateTimeTestCase
{
    /**
     * @return DateStorage
     * @covers ::__construct
     */
    public function testConstructor(): DateStorage
    {
        return new DateStorage(
            static::FORMAT,
            $this->createTimeZone()
        );
    }

    /**
     * @depends testConstructor
     *
     * @param DateStorage $storage
     *
     * @return void
     * @covers ::getHash
     *
     * @expectedException \InvalidArgumentException
     */
    public function testGetHashForIllegalArgument(DateStorage $storage)
    {
        /** @noinspection PhpParamsInspection */
        $storage->getHash(new stdClass());
    }

    /**
     * @return void
     * @covers ::getHash
     *
     * @expectedException \HylianShield\Date\IllegalDateTimeZoneException
     */
    public function testGetHashForMismatchingTimezone()
    {
        $expected = $this->createTimeZone(static::EXPECTED_TIMEZONE);
        $supplied = $this->createTimeZone(static::SUPPLIED_TIMEZONE);
        $storage  = new DateStorage(static::FORMAT, $expected);
        $date     = $this->createDateTime($supplied);

        $storage->getHash($date);
    }

    /**
     * @return string
     * @covers ::getHash
     */
    public function testGetHash(): string
    {
        $timeZone = $this->createTimeZone(static::EXPECTED_TIMEZONE);
        $storage  = new DateStorage(static::FORMAT, $timeZone);
        $date     = $this->createDateTime($timeZone, 42);

        return $storage->getHash($date);
    }
}
