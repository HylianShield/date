<?php
namespace HylianShield\Date;

use DateTimeZone;
use DomainException;
use Exception;

class IllegalDateTimeZoneException extends DomainException
{
    /**
     * Constructor.
     *
     * @param DateTimeZone $expected
     * @param DateTimeZone $supplied
     * @param int          $code
     * @param Exception    $previous
     */
    public function __construct(
        DateTimeZone $expected,
        DateTimeZone $supplied,
        int $code = 0,
        Exception $previous = null
    ) {
        parent::__construct(
            sprintf(
                'Date storage expects date in time zone %s, yet received %s',
                $expected->getName(),
                $supplied->getName()
            ),
            $code,
            $previous
        );
    }
}
