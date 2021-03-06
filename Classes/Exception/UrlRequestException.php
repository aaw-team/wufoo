<?php
namespace AawTeam\Wufoo\Exception;

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
 * UrlRequestException
 *
 * @author Agentur am Wasser | Maeder & Partner AG (development@agenturamwasser.ch)
 * @package AawTeam\Wufoo\Exception
 */
class UrlRequestException extends CanonicalUrlException
{
    /**
     * @var array
     */
    protected $report = [];

    /**
     * @param string $message
     * @param int $code
     * @param array $report
     * @param \Exception|\Throwable $previous
     */
    public function __construct($message = null, $code = null, array $report = null)
    {
        $previous = null;

        if ($report !== null) {
            $this->report = $report;

            if (\array_key_exists('exception', $report)) {
                $previous = $report['exception'];
            }
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getReport()
    {
        return $this->report;
    }
}
