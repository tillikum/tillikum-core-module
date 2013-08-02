<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Common\Contract;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Operations on contract signature collections
 */
final class Signatures
{
    /**
     * Create filter that keeps active contracts
     *
     * Active contracts are both <b>signed</b> and <b>not cancelled</b>.
     *
     * @return callable
     */
    public static function createIsActiveFilter()
    {
        return function ($signature) {
            // Signature is not signed
            if (!$signature->is_signed) {
                return false;
            }

            // Signature is cancelled
            if ($signature->is_cancelled) {
                return false;
            }

            return true;
        };
    }

    /**
     * Test if a collection of contracts are valid
     *
     * Valid contracts are <b>signed</b>, <b>not cancelled</b>, and
     * <b>co-signed</b> if necessary.
     *
     * @param  ArrayCollection $signatures
     * @return bool
     */
    public static function areValid(ArrayCollection $signatures)
    {
        $isActiveFilter = self::createIsActiveFilter();
        $partitioned = $signatures->filter($isActiveFilter)
            ->partition(
                function ($key, $signature) {
                    // Signature does not require a co-signature
                    if (!$signature->requires_cosigned) {
                        return true;
                    }

                    return $signature->is_cosigned;
                }
            );

        return count($partitioned[0]) > 0;
    }
}
