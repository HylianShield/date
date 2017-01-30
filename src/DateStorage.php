<?php
namespace HylianShield\Date;

use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use SplObjectStorage;

final class DateStorage extends SplObjectStorage implements DateStorageInterface
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
     * Constructor.
     *
     * @param string       $format
     * @param DateTimeZone $timeZone
     */
    public function __construct(string $format, DateTimeZone $timeZone)
    {
        $this->format   = $format;
        $this->timeZone = $timeZone;
    }

    /**
     * Get the hash for the supplied date.
     *
     * @param DateTimeInterface $object
     *
     * @return string
     * @throws InvalidArgumentException When $object does not implement
     *   DateTimeInterface.
     * @throws IllegalDateTimeZoneException When $object is not a date in the
     *   time zone as specified in the constructor of the storage.
     */
    public function getHash($object): string
    {
        if (!$object instanceof DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'Supplied argument "%s" does not implement "%s".',
                    is_object($object)
                        ? get_class($object)
                        : gettext($object),
                    DateTimeInterface::class
                )
            );
        }

        if ($object->getTimezone()->getName() !== $this->timeZone->getName()) {
            throw new IllegalDateTimeZoneException(
                $this->timeZone,
                $object->getTimezone()
            );
        }

        return $object->format($this->format);
    }
}
