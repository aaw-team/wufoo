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

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Form URL Utility
 *
 * @author Agentur am Wasser | Maeder & Partner AG (development@agenturamwasser.ch)
 * @package AawTeam\Wufoo\Utility
 */
class FormUrlUtility
{
    const FORMURL_REGEX = '~^https?:\\/\\/([^\\.]+)\\.wufoo\\.(?:com|eu)\\/forms\\/([^\\/]+)\\/?$~i';

    /**
     * @var array
     */
    protected static $urlVerificationCache = [];

    /**
     * Verifies whether $formUrl is a valid wufoo form URL or not. Additionally,
     * analysis result information is stored to $result.
     *
     * @param string $formUrl
     * @param array $result
     * @return boolean
     */
    public static function verifyUrl($formUrl, &$result = [])
    {
        if (!\is_string($formUrl)) {
            throw new \InvalidArgumentException('$formUrl must be string', 1495545943);
        }
        $urlHash = \sha1($formUrl);
        if (!array_key_exists($urlHash, self::$urlVerificationCache)) {
            self::$urlVerificationCache[$urlHash] = [
                'result' => (bool) \preg_match(self::FORMURL_REGEX, $formUrl, $matches),
                'username' => '',
                'formhash' => ''
            ];
            if (self::$urlVerificationCache[$urlHash]['result']) {
                self::$urlVerificationCache[$urlHash]['username'] = $matches[1];
                self::$urlVerificationCache[$urlHash]['formhash'] = $matches[2];
            }
        }
        $result = self::$urlVerificationCache[$urlHash];
        return self::$urlVerificationCache[$urlHash]['result'];
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
        if (!self::verifyUrl($formUrl, $result)) {
            throw new \InvalidArgumentException('$formUrl is not a valid wufoo form URL', 1495546005);
        }
        return $result;
    }

    /**
     * This method is EXPERIMENTAL!
     *
     * Returns the canonical URL for $formUrl (if possible). When no canonical
     * URL has been found, $formUrl will be returned.
     *
     * @param string $formUrl
     * @throws \InvalidArgumentException
     * @throws \AawTeam\Wufoo\Exception\UrlRequestException
     * @throws \AawTeam\Wufoo\Exception\DomLoadException
     * @return string
     */
    public static function getCanonicalUrl($formUrl)
    {
        if (!self::verifyUrl($formUrl)) {
            throw new \InvalidArgumentException('$formUrl is not a valid wufoo form URL', 1495546568);
        }

        $cacheId = sha1($formUrl);
        /** @var \TYPO3\CMS\Core\Cache\Frontend\FrontendInterface $cache */
        $cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('wufoo_canonicalUrl');
        if (($canonicalUrl = $cache->get($cacheId)) === false) {
            // Send the request
            $requestHeaders = [
                'Accept: text/html',
                'Pragma: no-cache',
                'Cache-Control: no-cache',
                'Connection: close',
                'User-Agent: TYPO3 Extension wufoo (https://github.com/aaw-team/wufoo)',
                'Referer: ' . GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL')
            ];
            $report = [];
            $responseBody = GeneralUtility::getUrl($formUrl, 0, $requestHeaders, $report);
            if (!$responseBody) {
                throw new \AawTeam\Wufoo\Exception\UrlRequestException('Cannot get contents of URL ' . \htmlspecialchars($formUrl), 1495549258, $report);
            }

            // Load the html content of the response
            $DOMDocument = new \DOMDocument();
            $previousLibxmlErrors = \libxml_use_internal_errors(true);
            if (!$DOMDocument->loadHTML($responseBody, LIBXML_NONET)) {
                throw new \AawTeam\Wufoo\Exception\DomLoadException('Cannot load HTML', 1495549433, \libxml_get_errors());
            }
            \libxml_use_internal_errors($previousLibxmlErrors);

            // Try to find the canonical URL
            $xpath = new \DOMXPath($DOMDocument);
            $canonicalUrl = $xpath->evaluate('/html/head/link[@rel="canonical"][1]/@href')->item(0)->value;

            // If no canonical URL is found, use $formUrl
            if (!\is_string($canonicalUrl)) {
                $canonicalUrl = $formUrl;
            }

            // Store into cache
            $cache->set($cacheId, $canonicalUrl);
        }

        return $canonicalUrl;
    }
}
