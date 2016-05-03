<?php
/**
 * HylianShield Date Storage.
 */
namespace HylianShield\Date;

use DateTimeInterface;

/**
 * Container for data storage along date instances.
 *
 * @package HylianShield\Date
 */
interface DateContainerInterface extends \Iterator
{
    /**
     * Attach the given data along the given date.
     *
     * @param DateTimeInterface $date
     * @param mixed $data
     * @return $this
     */
    public function attach(DateTimeInterface $date, $data);

    /**
     * Detach the data corresponding to the given date.
     *
     * @param DateTimeInterface $date
     * @return $this
     */
    public function detach(DateTimeInterface $date);

    /**
     * Check if the container contains data for the given date.
     *
     * @param DateTimeInterface $date
     * @return bool
     */
    public function contains(DateTimeInterface $date);

    /**
     * Get the data for the given date.
     *
     * @param DateTimeInterface $date
     * @return mixed|null
     */
    public function getData(DateTimeInterface $date);

    /**
     * Return the date for the current data.
     *
     * @return null|DateTimeInterface
     */
    public function key();

    /**
     * Get the unique identifier for the given date.
     *
     * @param DateTimeInterface $date
     * @return string
     */
    public function getIdentifier(DateTimeInterface $date);

    /**
     * Get all unique date identifiers.
     *
     * @return string[]
     */
    public function getIdentifiers();

    /**
     * Convert the storage to an array.
     *
     * @return mixed[]
     */
    public function toArray();
}
