<?php
namespace HylianShield\Date\Tests;

use HylianShield\Date\IllegalDateTimeZoneException;

/**
 * @coversDefaultClass \HylianShield\Date\IllegalDateTimeZoneException
 */
class IllegalDateTimeZoneExceptionTest extends AbstractDateTimeTestCase
{
    /**
     * @return IllegalDateTimeZoneException
     * @covers ::__construct
     */
    public function testConstructor(): IllegalDateTimeZoneException
    {
        return new IllegalDateTimeZoneException(
            $this->createTimeZone(),
            $this->createTimeZone()
        );
    }
}
