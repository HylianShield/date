<?php
/**
 * Copyright MediaCT. All rights reserved.
 * https://www.mediact.nl
 */

namespace HylianShield\Date;

use DateTimeInterface;
use DateTimeZone;

/**
 * A storage container for date related data.
 *
 * @package HylianShield\Date
 */
final class DateStorage extends \SplObjectStorage implements DateStorageInterface
{
    /**
     * The date format that makes dates uniquely identifiable.
     *
     * @var string
     * @see http://php.net/manual/en/datetime.formats.php
     */
    private $format;

    /**
     * The time zone to which all dates must adhere.
     *
     * @var DateTimeZone
     */
    private $timeZone;

    /**
     * DateStorage constructor.
     *
     * @param string $format
     * @param DateTimeZone $timeZone
     */
    public function __construct($format, DateTimeZone $timeZone)
    {
        if (!is_string($format)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid date format supplied: %s', gettype($format))
            );
        }

        $this->format = $format;
        $this->timeZone = $timeZone;
    }

    /**
     * Get the hash for the supplied date.
     *
     * @param DateTimeInterface $object
     * @return string
     * @throws \DomainException when $object is not a date in the time zone
     *   as specified in the constructor of the storage.
     */
    public function getHash(DateTimeInterface $object)
    {
        if ($object->getTimezone()->getName() !== $this->timeZone->getName()) {
            throw new \DomainException(
                sprintf(
                    'Date storage expects date in time zone %s, yet received %s',
                    $this->timeZone->getName(),
                    $object->getTimezone()->getName()
                )
            );
        }

        return $object->format($this->format);
    }
}
