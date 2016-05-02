<?php
/**
 * HylianShield Date Storage.
 */
namespace HylianShield\Date;

use DateTimeInterface;

/**
 * Interface for date storage container entities.
 *
 * @package HylianShield\Date
 */
interface DateStorageInterface extends \Iterator, \ArrayAccess
{
    /**
     * Get the hash for the supplied date.
     *
     * @param DateTimeInterface $object
     * @return string
     * @throws \InvalidArgumentException when $object is not an instance of
     *   DateTimeInterface.
     */
    public function getHash($object);
}
