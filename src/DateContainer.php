<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace HylianShield\Date;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * Container for data storage along date instances.
 *
 * @package HylianShield\Date
 */
class DateContainer implements DateContainerInterface
{
    /**
     * @var DateStorageInterface
     */
    private $storage;

    /**
     * DateContainer constructor.
     *
     * @param DateStorageInterface $storage
     */
    public function __construct(DateStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Storage getter.
     *
     * @return DateStorageInterface
     */
    final protected function getStorage()
    {
        return $this->storage;
    }

    /**
     * Attach the given data along the given date.
     *
     * @param DateTimeInterface $date
     * @param mixed $data
     * @return $this
     */
    public function attach(DateTimeInterface $date, $data)
    {
        if ($date instanceof DateTime) {
            // Ensure that the date cannot be modified outside of the container.
            $date = DateTimeImmutable::createFromMutable($date);
        }

        $this->getStorage()->offsetSet($date, $data);

        return $this;
    }

    /**
     * Detach the data corresponding to the given date.
     *
     * @param DateTimeInterface $date
     * @return $this
     */
    public function detach(DateTimeInterface $date)
    {
        $this->getStorage()->offsetUnset($date);

        return $this;
    }

    /**
     * Check if the container contains data for the given date.
     *
     * @param DateTimeInterface $date
     * @return bool
     */
    public function contains(DateTimeInterface $date)
    {
        return $this->getStorage()->offsetExists($date);
    }

    /**
     * Get the data for the given date.
     *
     * @param DateTimeInterface $date
     * @return mixed|null
     */
    public function getData(DateTimeInterface $date)
    {
        return ($this->contains($date)
            ? $this->getStorage()->offsetGet($date)
            : null
        );
    }

    /**
     * Return the current data.
     *
     * @return mixed
     */
    public function current()
    {
        return $this->getData($this->key());
    }

    /**
     * Move forward to next element.
     *
     * @return void
     */
    public function next()
    {
        $this->getStorage()->next();
    }

    /**
     * Return the date for the current data.
     *
     * @return null|DateTimeInterface
     */
    public function key()
    {
        return $this->getStorage()->current();
    }

    /**
     * Checks if current position is valid.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->getStorage()->valid();
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @return void
     */
    public function rewind()
    {
        $this->getStorage()->rewind();
    }

    /**
     * Get the unique identifier for the given date.
     *
     * @param DateTimeInterface $date
     * @return string
     */
    public function getIdentifier(DateTimeInterface $date)
    {
        return $this->getStorage()->getHash($date);
    }

    /**
     * Get all unique date identifiers.
     *
     * @return string[]
     */
    public function getIdentifiers()
    {
        $identifiers = [];

        foreach ($this->getStorage() as $date) {
            $identifiers[] = $this->getIdentifier($date);
        }

        return $identifiers;
    }

    /**
     * Convert the storage to an array.
     *
     * @return mixed[]
     */
    public function toArray()
    {
        $storage = [];

        /** @var DateTimeInterface $date */
        foreach ($this as $date => $data) {
            $storage[$this->getIdentifier($date)] = $data;
        }

        return $storage;
    }
}
