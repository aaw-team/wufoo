<?php
namespace AawTeam\Wufoo\Utility;
/**
 *  Copyright notice
 *
 *  (c) 2016 Agentur am Wasser | Maeder & Partner AG (development@agenturamwasser.ch)
 *  All rights reserved
 *
 *  You may not remove or change the name of the author above. See:
 *  http://www.gnu.org/licenses/gpl-faq.html#IWantCredit
 *
 *  This script is part of the Typo3 project. The Typo3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */

use TYPO3\CMS\Extbase\Utility\LocalizationUtility as ExtbaseLocalizationUtility;

/**
 * Localization Utility
 *
 * @author Agentur am Wasser | Maeder & Partner AG (development@agenturamwasser.ch)
 * @package AawTeam\Wufoo\Utility
 */
class LocalizationUtility
{
    /**
     * Returns the localized label of $key
     *
     * @param string $key
     * @param string|array|null $arguments
     * @throws \InvalidArgumentException
     * @return string
     */
    public static function translate($key, $arguments = null)
    {
        if (is_string($arguments)) {
            $arguments = [$arguments];
        } elseif (!is_array($arguments) && !is_null($arguments)) {
            throw new \InvalidArgumentException('$arguments must be string, array or null');
        }
        $translated = ExtbaseLocalizationUtility::translate($key, 'wufoo', $arguments);
        if ($translated === null) {
            $translated = $key;
        }
        return $translated;
    }
}
