<?php
namespace HylianShield\Date;

use DateTimeInterface;
use Iterator;

interface DateContainerInterface extends Iterator
{
    /**
     * Attach the given data along the given date.
     *
     * @param DateTimeInterface $date
     * @param mixed             $data
     *
     * @return void
     */
    public function attach(DateTimeInterface $date, $data);

    /**
     * Detach the data corresponding to the given date.
     *
     * @param DateTimeInterface $date
     *
     * @return void
     */
    public function detach(DateTimeInterface $date);

    /**
     * Check if the container contains data for the given date.
     *
     * @param DateTimeInterface $date
     *
     * @return bool
     */
    public function contains(DateTimeInterface $date): bool;

    /**
     * Get the data for the given date.
     *
     * @param DateTimeInterface $date
     *
     * @return mixed
     */
    public function getData(DateTimeInterface $date);

    /**
     * Return the date for the current data.
     *
     * @return DateTimeInterface
     */
    public function key(): DateTimeInterface;

    /**
     * Get the unique identifier for the given date.
     *
     * @param DateTimeInterface $date
     *
     * @return string
     */
    public function getIdentifier(DateTimeInterface $date): string;

    /**
     * Convert the storage to an array.
     *
     * @return mixed[]
     */
    public function toArray(): array;
}
