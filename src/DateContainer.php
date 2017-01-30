<?php
namespace HylianShield\Date;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

class DateContainer implements DateContainerInterface
{
    /** @var DateStorageInterface */
    private $storage;

    /**
     * Constructor.
     *
     * @param DateStorageInterface $storage
     */
    public function __construct(DateStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Attach the given data along the given date.
     *
     * @param DateTimeInterface $date
     * @param mixed             $data
     *
     * @return void
     */
    public function attach(DateTimeInterface $date, $data)
    {
        if ($date instanceof DateTime) {
            // Ensure that the date cannot be modified outside of the container.
            $date = DateTimeImmutable::createFromMutable($date);
        }

        $this->storage->offsetSet($date, $data);
    }

    /**
     * Detach the data corresponding to the given date.
     *
     * @param DateTimeInterface $date
     *
     * @return void
     */
    public function detach(DateTimeInterface $date)
    {
        $this->storage->offsetUnset($date);
    }

    /**
     * Check if the container contains data for the given date.
     *
     * @param DateTimeInterface $date
     *
     * @return bool
     */
    public function contains(DateTimeInterface $date): bool
    {
        return $this->storage->offsetExists($date);
    }

    /**
     * Get the data for the given date.
     *
     * @param DateTimeInterface $date
     *
     * @return mixed
     */
    public function getData(DateTimeInterface $date)
    {
        return ($this->contains($date)
            ? $this->storage->offsetGet($date)
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
        $this->storage->next();
    }

    /**
     * Return the date for the current data.
     *
     * @return DateTimeInterface
     */
    public function key(): DateTimeInterface
    {
        return $this->storage->current();
    }

    /**
     * Checks if current position is valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        return $this->storage->valid();
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @return void
     */
    public function rewind()
    {
        $this->storage->rewind();
    }

    /**
     * Get the unique identifier for the given date.
     *
     * @param DateTimeInterface $date
     *
     * @return string
     */
    public function getIdentifier(DateTimeInterface $date): string
    {
        return $this->storage->getHash($date);
    }

    /**
     * Convert the storage to an array.
     *
     * @return mixed[]
     */
    public function toArray(): array
    {
        $storage = [];

        foreach ($this as $date => $data) {
            $storage[$this->getIdentifier($date)] = $data;
        }

        return $storage;
    }
}
