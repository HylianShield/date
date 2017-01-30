<?php
namespace HylianShield\Date;

use DateTimeInterface;

interface DateStorageInterface extends \Iterator, \ArrayAccess
{
    /**
     * Get the hash for the supplied date.
     *
     * @param DateTimeInterface $object
     *
     * @return string
     */
    public function getHash($object): string;
}
