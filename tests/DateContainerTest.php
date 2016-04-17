<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace HylianShield\Test\Date;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use HylianShield\Date\DateContainer;
use HylianShield\Date\DateStorageInterface;

class DateContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|DateStorageInterface
     */
    protected function createStorageMock()
    {
        return $this->getMock(DateStorageInterface::class);
    }

    /**
     * Test the constructor.
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(
            DateContainer::class,
            new DateContainer($this->createStorageMock())
        );
    }

    /**
     * @return mixed[][]
     */
    public function attachProvider()
    {
        $calls = [];

        $mutableDate = new DateTime('1234-01-01 12:34:56');
        $immutableDate = DateTimeImmutable::createFromMutable($mutableDate);

        $entries = [
            'Foo',
            1337,
            .12,
            [],
            new \stdClass()
        ];

        foreach ($entries as $entry) {
            $calls[] = [$mutableDate, $entry];
            $calls[] = [$immutableDate, $entry];
        }

        return $calls;
    }

    /**
     * Test the attach method.
     *
     * @param DateTimeInterface $dateTime
     * @param mixed $data
     * @dataProvider attachProvider
     */
    public function testAttach(DateTimeInterface $dateTime, $data = null)
    {
        $storage = $this->createStorageMock();
        $container = new DateContainer($storage);

        $storage
            ->expects($this->once())
            ->method('offsetSet')
            ->with(
                $this->isInstanceOf(DateTimeImmutable::class),
                $data
            );

        $this->assertInstanceOf(
            DateContainer::class,
            $container->attach($dateTime, $data)
        );
    }

    /**
     * Test the detach method.
     */
    public function testDetach()
    {
        $dateTime = new DateTime();
        $storage = $this->createStorageMock();
        $container = new DateContainer($storage);

        $storage
            ->expects($this->once())
            ->method('offsetUnset')
            ->with($dateTime);

        $this->assertInstanceOf(
            DateContainer::class,
            $container->detach($dateTime)
        );
    }

    /**
     * Test the container against a storage that does contain a supplied date.
     */
    public function testDoesContain()
    {
        $storage = $this->createStorageMock();
        $dateTime = new DateTime();

        $storage
            ->expects($this->once())
            ->method('offsetExists')
            ->with($dateTime)
            ->willReturn(true);

        $container = new DateContainer($storage);

        $this->assertTrue($container->contains($dateTime));
    }

    /**
     * Test the container against a storage that does not contain a supplied
     * date.
     */
    public function testDoesNotContain()
    {
        $storage = $this->createStorageMock();
        $dateTime = new DateTime();

        $storage
            ->expects($this->once())
            ->method('offsetExists')
            ->with($dateTime)
            ->willReturn(false);

        $container = new DateContainer($storage);

        $this->assertFalse($container->contains($dateTime));
    }

    /**
     * Test getting existing data out of the container.
     */
    public function testGetExistingData()
    {
        $storage = $this->createStorageMock();
        $dateTime = new DateTime();
        $data = ['foo' => 'bar'];

        $storage
            ->expects($this->once())
            ->method('offsetExists')
            ->with($dateTime)
            ->willReturn(true);

        $storage
            ->expects($this->once())
            ->method('offsetGet')
            ->with($dateTime)
            ->willReturn($data);

        $container = new DateContainer($storage);

        $this->assertSame(
            $data,
            $container->getData($dateTime)
        );
    }

    /**
     * Test getting non-existing data out of the container.
     */
    public function testGetNonExistingData()
    {
        $storage = $this->createStorageMock();
        $dateTime = new DateTime();

        $storage
            ->expects($this->once())
            ->method('offsetExists')
            ->with($dateTime)
            ->willReturn(false);

        $storage
            ->expects($this->never())
            ->method('offsetGet');

        $container = new DateContainer($storage);

        $this->assertNull(
            $container->getData($dateTime)
        );
    }

    /**
     * Test getting the identifier.
     */
    public function testGetIdentifier()
    {
        $dateTime = new DateTime();
        $identifier = $dateTime->format('Y-m-d');

        $storage = $this->createStorageMock();
        $storage
            ->expects($this->once())
            ->method('getHash')
            ->with($dateTime)
            ->willReturn($identifier);

        $container = new DateContainer($storage);

        $this->assertEquals($identifier, $container->getIdentifier($dateTime));
    }

    /**
     * Create a storage holding the given data points, all having the same
     * given data.
     *
     * @param DateTimeInterface[] $dataPoints
     * @param mixed $data
     * @return DateStorageInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createIterableStorageMock(array $dataPoints, $data)
    {
        $storage = $this->createStorageMock();

        $validReturns = array_map('boolval', $dataPoints);
        $validReturns[] = false;

        $currentReturns = [];

        foreach ($dataPoints as $dataPoint) {
            if ($dataPoint instanceof DateTime) {
                $dataPoint = DateTimeImmutable::createFromMutable($dataPoint);
            }

            $currentReturns[] = $dataPoint;
            $currentReturns[] = $dataPoint;
        }

        $storage
            ->expects($this->exactly(count($currentReturns)))
            ->method('current')
            ->willReturnOnConsecutiveCalls(...$currentReturns);

        $storage
            ->expects($this->exactly(count($validReturns)))
            ->method('valid')
            ->willReturnOnConsecutiveCalls(...$validReturns);

        $storage
            ->expects($this->exactly(count($dataPoints)))
            ->method('next');

        $storage
            ->expects($this->exactly(count($dataPoints)))
            ->method('offsetExists')
            ->willReturn(true);

        $storage
            ->expects($this->exactly(count($dataPoints)))
            ->method('offsetGet')
            ->willReturn($data);

        return $storage;
    }

    /**
     * Test iterating over a date container.
     */
    public function testIteration()
    {
        $dataValue = 'foo';
        $dataPoints = [
            new DateTime('today'),
            new DateTime('tomorrow')
        ];

        $storage = $this->createIterableStorageMock($dataPoints, $dataValue);

        $container = new DateContainer($storage);
        $numIterations = 0;

        foreach ($container as $date => $data) {
            $this->assertEquals($dataValue, $data);
            $this->assertInstanceOf(DateTimeImmutable::class, $date);

            ++$numIterations;
        }

        $this->assertEquals(count($dataPoints), $numIterations);
    }

    /**
     * Test the toArray functionality.
     *
     * @covers \HylianShield\Date\DateContainer::toArray
     */
    public function testToArray()
    {
        $dataValue = 'foo';
        /** @var DateTimeInterface[] $dataPoints */
        $dataPoints = [
            new DateTime('today'),
            new DateTime('tomorrow')
        ];
        $format = 'Y-m-d';

        $storage = $this->createIterableStorageMock($dataPoints, $dataValue);
        $container = new DateContainer($storage);

        $storage
            ->expects($this->exactly(count($dataPoints)))
            ->method('getHash')
            ->with($this->isInstanceOf(DateTimeImmutable::class))
            ->willReturnCallback(
                function (DateTimeInterface $dateTime) use ($format) {
                    return $dateTime->format($format);
                }
            );

        $dataSet = $container->toArray();

        $this->assertInternalType('array', $dataSet);
        $this->assertCount(count($dataPoints), $dataSet);

        foreach ($dataPoints as $date) {
            $this->assertArrayHasKey($date->format($format), $dataSet);
        }

        foreach ($dataSet as $data) {
            $this->assertEquals($dataValue, $data);
        }
    }

    /**
     * Test getting the identifiers for the stored dates.
     *
     * @covers \HylianShield\Date\DateContainer::getIdentifiers
     */
    public function testGetIdentifiers()
    {
        /** @var DateTimeInterface[] $dataPoints */
        $dataPoints = [
            new DateTimeImmutable('today'),
            new DateTimeImmutable('tomorrow')
        ];
        $format = 'Y-m-d';
        $validReturns = array_map('boolval', $dataPoints);
        $validReturns[] = false;

        $formatter = function (DateTimeInterface $dateTime) use ($format) {
            return $dateTime->format($format);
        };

        $storage = $this->createStorageMock();

        $storage
            ->expects($this->exactly(count($dataPoints)))
            ->method('current')
            ->willReturnOnConsecutiveCalls(...$dataPoints);

        $storage
            ->expects($this->exactly(count($validReturns)))
            ->method('valid')
            ->willReturnOnConsecutiveCalls(...$validReturns);

        $storage
            ->expects($this->exactly(count($dataPoints)))
            ->method('getHash')
            ->with($this->isInstanceOf(DateTimeImmutable::class))
            ->willReturnCallback($formatter);

        $container = new DateContainer($storage);
        $identifiers = $container->getIdentifiers();

        $this->assertSame(
            array_map($formatter, $dataPoints),
            $identifiers
        );
    }
}
