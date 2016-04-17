<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
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
     */
    public function getHash(DateTimeInterface $object);
}
