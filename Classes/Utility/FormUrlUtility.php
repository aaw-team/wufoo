<?php
namespace AawTeam\Wufoo\Utility;

/**
 *  Copyright notice
 *
 *  (c) 2017 Agentur am Wasser | Maeder & Partner AG (development@agenturamwasser.ch)
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

/**
 * Form URL Utility
 *
 * @author Agentur am Wasser | Maeder & Partner AG (development@agenturamwasser.ch)
 * @package AawTeam\Wufoo\Utility
 */
class FormUrlUtility
{
    /**
     * Verifies whether $formUrl is a valid wufoo form URL or not.
     *
     * @param string $formUrl
     * @return boolean
     */
    public static function verifyUrl($formUrl)
    {
        if (!\is_string($formUrl)) {
            throw new \InvalidArgumentException('$formUrl must be string', 1495545943);
        }
        return (bool) \preg_match('~^https?:\\/\\/[^\\.]+\\.wufoo\\.(?:com|eu)\\/forms\\/[^\\/]+\\/?$~i', $formUrl);
    }

    /**
     * Returns an array with the username and the formhash from $formUrl.
     *
     * @param string $formUrl
     * @throws \InvalidArgumentException
     * @return array
     */
    public static function extractData($formUrl)
    {
        if (!self::verifyUrl($formUrl)) {
            throw new \InvalidArgumentException('$formUrl is not a valid wufoo form URL', 1495546005);
        }

        \preg_match('~^https?:\\/\\/([^\\.]+)\\.wufoo\\.(?:com|eu)\\/forms\\/([^\\/]+)\\/?$~i', $formUrl, $matches);

        return [
            'username' => $matches[1],
            'formhash' => $matches[2],
        ];
    }
}
