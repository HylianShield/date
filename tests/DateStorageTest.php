<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace HylianShield\Test\Date;

use DateTime;
use DateTimeZone;
use HylianShield\Date\DateStorage;

class DateStorageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return DateTimeZone
     */
    protected function createDateTimeZone()
    {
        $identifiers = DateTimeZone::listIdentifiers(DateTimeZone::UTC);
        return new DateTimeZone(current($identifiers));
    }

    /**
     * @return mixed[][]
     */
    public function illegalConstructorArgumentsProvider()
    {
        $dateTimeZone = $this->createDateTimeZone();

        return array_map(
            function ($illegalFormat) use ($dateTimeZone) {
                return [$illegalFormat, $dateTimeZone];
            },
            [
                12,
                .12,
                true,
                false,
                [],
                new \stdClass()
            ]
        );
    }

    /**
     * Test that the constructor throws when an illegal date format is supplied.
     *
     * @param mixed $format
     * @param DateTimeZone $dateTimeZone
     * @dataProvider illegalConstructorArgumentsProvider
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid date format supplied
     */
    public function testIllegalConstructorFormat(
        $format,
        DateTimeZone $dateTimeZone
    ) {
        new DateStorage($format, $dateTimeZone);
    }

    /**
     * @return mixed[][]
     */
    public function formatAndTimeZoneProvider()
    {
        $dateTimeZone = $this->createDateTimeZone();

        return array_map(
            function ($format) use ($dateTimeZone) {
                return [$format, $dateTimeZone];
            },
            // @see http://php.net/manual/en/datetime.formats.php
            [
                'foo',
                'Y-m-d',
                'Y-m-d H:i:s',
                'r',
                'c'
            ]
        );
    }

    /**
     * Test the hashing function against the given format and date time zone.
     *
     * @param string $format
     * @param DateTimeZone $dateTimeZone
     * @dataProvider formatAndTimeZoneProvider
     */
    public function testHash($format, DateTimeZone $dateTimeZone)
    {
        $storage = new DateStorage($format, $dateTimeZone);
        $dateTime = new DateTime('2000-01-01 13:37:42', $dateTimeZone);
        $this->assertEquals(
            $dateTime->format($format),
            $storage->getHash($dateTime)
        );
    }

    /**
     * Test that the getHash method throws when an ilegal object is supplied.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid object supplied. Must be instance of
     */
    public function testInvalidHashingObject()
    {
        $storage = new DateStorage('Y-m-d', $this->createDateTimeZone());
        $storage->getHash(false);
    }

    /**
     * @return DateTimeZone[][]
     */
    public function mismatchingDateTimeZoneProvider()
    {
        $identifiers = DateTimeZone::listIdentifiers();
        $calls = [];

        while (count($identifiers) > 1) {
            $calls[] = [
                new DateTimeZone(array_shift($identifiers)),
                new DateTimeZone(array_shift($identifiers))
            ];
        }

        return $calls;
    }

    /**
     * Test that the storage throws when date time instances, that use a
     * differing time zone to the storage, are supplied, the storage throws.
     *
     * @param DateTimeZone $originalDateTimeZone
     * @param DateTimeZone $mismatchDateTimeZone
     * @dataProvider mismatchingDateTimeZoneProvider
     * @expectedException \DomainException
     * @expectedExceptionMessage Date storage expects date in time zone
     * @covers \HylianShield\Date\DateStorage::getHash
     */
    public function testMismatchingDateTimeZones(
        DateTimeZone $originalDateTimeZone,
        DateTimeZone $mismatchDateTimeZone
    ) {
        $storage = new DateStorage('', $originalDateTimeZone);
        $dateTime = new DateTime('now', $mismatchDateTimeZone);
        $storage->getHash($dateTime);
    }
}
