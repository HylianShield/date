<?php
/**
 * HylianShield Date Storage.
 */

namespace HylianShield\Test\Date;

use DateTime;
use DateTimeZone;
use HylianShield\Date\DateContainerFactory;
use HylianShield\Date\DateContainerInterface;

class DateContainerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return DateTimeZone[][]|null[][]
     */
    public function constructorArgumentsProvider()
    {
        $dateTimeZones = array_map(
            function ($identifier) {
                return [new DateTimeZone($identifier)];
            },
            DateTimeZone::listIdentifiers()
        );

        $dateTimeZones[] = [null];

        return $dateTimeZones;
    }

    /**
     * Test the constructor.
     *
     * @param DateTimeZone|null $dateTimeZone
     * @dataProvider constructorArgumentsProvider
     */
    public function testConstructor(DateTimeZone $dateTimeZone = null)
    {
        $this->assertInstanceOf(
            DateContainerFactory::class,
            new DateContainerFactory($dateTimeZone)
        );
    }

    /**
     * Assert that the given container uses the given format.
     *
     * @param DateContainerInterface $container
     * @param string $format
     * @covers \HylianShield\Date\DateContainerFactory::createFromFormat
     */
    protected function assertDateContainerUsesFormat(
        DateContainerInterface $container,
        $format
    ) {
        $dateTime = new DateTime('1234-01-01 12:34:56');

        $this->assertEquals(
            $dateTime->format($format),
            $container->getIdentifier($dateTime)
        );
    }

    /**
     * @return DateContainerFactory[][]
     */
    public function factoryProvider()
    {
        return [
            [new DateContainerFactory()]
        ];
    }

    /**
     * Test the createFromFormat method.
     *
     * @param DateContainerFactory $factory
     * @dataProvider factoryProvider
     * @covers \HylianShield\Date\DateContainerFactory::createFromFormat
     */
    public function testCreateFromFormat(DateContainerFactory $factory)
    {
        $format = 'YYYY-MMMMM-DDDDDDD-HH-ii:ssss';

        $this->assertDateContainerUsesFormat(
            $factory->createFromFormat($format),
            $format
        );
    }

    /**
     * Test the createIntervalSecond method.
     *
     * @param DateContainerFactory $factory
     * @dataProvider factoryProvider
     * @covers \HylianShield\Date\DateContainerFactory::createIntervalSecond
     */
    public function testCreateIntervalSecond(DateContainerFactory $factory)
    {
        $this->assertDateContainerUsesFormat(
            $factory->createIntervalSecond(),
            'Y-m-d H:i:s'
        );
    }

    /**
     * Test the createIntervalMinute method.
     *
     * @param DateContainerFactory $factory
     * @dataProvider factoryProvider
     * @covers \HylianShield\Date\DateContainerFactory::createIntervalMinute
     */
    public function testCreateIntervalMinute(DateContainerFactory $factory)
    {
        $this->assertDateContainerUsesFormat(
            $factory->createIntervalMinute(),
            'Y-m-d H:i'
        );
    }

    /**
     * Test the createIntervalHour method.
     *
     * @param DateContainerFactory $factory
     * @dataProvider factoryProvider
     * @covers \HylianShield\Date\DateContainerFactory::createIntervalHour
     */
    public function testCreateIntervalHour(DateContainerFactory $factory)
    {
        $this->assertDateContainerUsesFormat(
            $factory->createIntervalHour(),
            'Y-m-d H'
        );
    }

    /**
     * Test the createIntervalDay method.
     *
     * @param DateContainerFactory $factory
     * @dataProvider factoryProvider
     * @covers \HylianShield\Date\DateContainerFactory::createIntervalDay
     */
    public function testCreateIntervalDay(DateContainerFactory $factory)
    {
        $this->assertDateContainerUsesFormat(
            $factory->createIntervalDay(),
            'Y-m-d'
        );
    }
}
