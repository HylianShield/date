<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace HylianShield\Date\Tests;

use DateTime;
use DateTimeInterface;
use HylianShield\Date\DateContainer;
use HylianShield\Date\DateStorageInterface;

/**
 * @coversDefaultClass \HylianShield\Date\DateContainer
 */
class DateContainerTest extends AbstractDateTimeTestCase
{
    /**
     * @return DateContainer
     * @covers ::__construct
     */
    public function testConstructor(): DateContainer
    {
        return new DateContainer(
            $this->createMock(DateStorageInterface::class)
        );
    }

    /**
     * @depends testConstructor
     *
     * @param DateContainer $container
     *
     * @return string
     * @covers ::getIdentifier
     */
    public function testGetIdentifier(DateContainer $container): string
    {
        return $container->getIdentifier(
            $this->createDateTime(null, 42)
        );
    }

    /**
     * @return DateContainer
     * @covers ::attach
     */
    public function testAttach(): DateContainer
    {
        $storage   = $this->createMock(DateStorageInterface::class);
        $container = new DateContainer($storage);
        $timeZone = $this->createTimeZone();

        $foo = $this->createDateTime($timeZone, strtotime('1970-01-01'));
        $bar = $this->createDateTime($timeZone, strtotime('1970-01-02'));
        $baz = new DateTime('1970-01-03', $timeZone);

        $container->attach($foo, 'Foo');
        $container->attach($bar, 'Bar');
        $container->attach($baz, 'Baz');

        $storage
            ->expects($this->any())
            ->method('getHash')
            ->with($this->isInstanceOf(DateTimeInterface::class))
            ->willReturnCallback(
                function (DateTimeInterface $dateTime) : string {
                    return $dateTime->format(static::FORMAT);
                }
            );

        $storage
            ->expects($this->any())
            ->method('offsetExists')
            ->with($this->isInstanceOf(DateTimeInterface::class))
            ->willReturnCallback(
                function (DateTimeInterface $dateTime) : bool {
                    return in_array(
                        $dateTime->format('d'),
                        ['01', '02'],
                        true
                    );
                }
            );

        $storage
            ->expects($this->any())
            ->method('offsetGet')
            ->with($this->isInstanceOf(DateTimeInterface::class))
            ->willReturnCallback(
                function (DateTimeInterface $dateTime) {
                    static $values = [
                        '01' => 'Foo',
                        '02' => 'Bar'
                    ];

                    $day = $dateTime->format('d');

                    return array_key_exists($day, $values)
                        ? $values[$day]
                        : null;
                }
            );

        $storage
            ->expects($this->any())
            ->method('current')
            ->willReturnOnConsecutiveCalls(
                // First iteration.
                $foo,
                $bar,

                // Second iteration.
                $foo,
                $bar
            );

        $storage
            ->expects($this->any())
            ->method('valid')
            ->willReturn(
                // First iteration.
                true,
                true,
                false,

                // Second iteration.
                true,
                true,
                false
            );

        return $container;
    }

    /**
     * @depends clone testAttach
     *
     * @param DateContainer $container
     *
     * @return DateContainer
     * @covers ::detach
     */
    public function testDetach(DateContainer $container): DateContainer
    {
        $container->detach(
            $this->createDateTime(null, strtotime('1970-01-03'))
        );

        $this->assertCount(2, $container);

        return $container;
    }

    /**
     * @depends clone testAttach
     *
     * @param DateContainer $container
     *
     * @return bool
     * @covers ::contains
     */
    public function testContains(DateContainer $container): bool
    {
        return $container->contains(
            $this->createDateTime(null, strtotime('1970-01-01'))
        );
    }

    /**
     * @depends clone testAttach
     *
     * @param DateContainer $container
     *
     * @return void
     * @covers ::getData
     */
    public function testGetData(DateContainer $container)
    {
        $this->assertEquals(
            'Foo',
            $container->getData(
                $this->createDateTime(null, strtotime('1970-01-01'))
            )
        );

        $this->assertEquals(
            'Bar',
            $container->getData(
                $this->createDateTime(null, strtotime('1970-01-02'))
            )
        );

        $this->assertNull(
            $container->getData(
                $this->createDateTime(null, strtotime('1970-01-03'))
            )
        );
    }

    /**
     * @depends clone testAttach
     *
     * @param DateContainer $container
     *
     * @return array
     * @covers ::toArray
     * @covers ::rewind
     * @covers ::valid
     * @covers ::current
     * @covers ::next
     * @covers ::key
     */
    public function testToArray(DateContainer $container): array
    {
        return $container->toArray();
    }
}
