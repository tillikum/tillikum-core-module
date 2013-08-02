<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\DBAL\Types;

use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

class UTCDateTimeType extends DateTimeType
{
    const NAME = 'utcdatetime';

    private static $utcInstance;

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $value->setTimezone($this->getUTCInstance());

        return $value->format($platform->getDateTimeFormatString());
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $val = DateTime::createFromFormat(
            $platform->getDateTimeFormatString(),
            $value,
            $this->getUTCInstance()
        );

        if ($val === false) {
            throw ConversionException::conversionFailedFormat(
                $value, $this->getName(), $platform->getDateTimeFormatString()
            );
        }

        return $val;
    }

    public function getName()
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    private function getUTCInstance()
    {
        if (self::$utcInstance === null) {
            self::$utcInstance = new DateTimeZone('UTC');
        }

        return self::$utcInstance;
    }
}
