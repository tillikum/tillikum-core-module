<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Version;

final class Version
{
    /**
     * Current Tillikum version
     */
    const VERSION = '0.10.0dev';

    /**
     * Compares a Tillikum version with the current one.
     *
     * @param  string $version Tillikum version to compare.
     * @return int    Returns -1 if older, 0 if it is the same, 1 if version
     *                passed as argument is newer.
     */
    public static function compare($version)
    {
        return version_compare(
            strtolower($version),
            strtolower(self::VERSION)
        );
    }
}
