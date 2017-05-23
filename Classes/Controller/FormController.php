<?php
namespace AawTeam\Wufoo\Controller;
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

use AawTeam\Wufoo\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Form Controller
 *
 * @author Agentur am Wasser | Maeder & Partner AG (development@agenturamwasser.ch)
 * @package AawTeam\Wufoo\Controller
 */
class FormController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * @return void|string
     */
    public function indexAction()
    {
        $formUrlParsed = parse_url($this->settings['formUrl']);
        if (!$formUrlParsed) {
            return LocalizationUtility::translate('error.invalidFormUrl');
        }

        // Find username
        $matches = [];
        if (!preg_match('/^([^\\.]+)\\.wufoo\\.(?:com|eu)$/i', $formUrlParsed['host'], $matches)) {
            return LocalizationUtility::translate('error.noUsername');
        }
        $username = $matches[1];

        // Find formhash
        $matches = [];
        if (!preg_match('~^/forms/([^/]+)/?$~i', $formUrlParsed['path'], $matches)) {
            return LocalizationUtility::translate('error.noFormhash');
        }
        $formhash = $matches[1];

        // Set height
        $height = 500;
        if (!$this->settings['autoresize'] && MathUtility::canBeInterpretedAsInteger($this->settings['height']) && $this->settings['height'] > 0) {
            $height = $this->settings['height'];
        }

        // create the javascript
        $js = "
var " . $formhash . ";(function(d, t) {
var s = d.createElement(t), options = {
    'userName':'" . $username . "',
    'formHash':'" . $formhash . "',
    'autoResize':" . ($this->settings['autoresize'] ? 'true' : 'false') . ",
    'height':'" . $height . "',
    'async':true,
    'host':'wufoo.com',
    'header':'" . ($this->settings['showHeader'] ? 'show' : 'hide') . "',
    'ssl':true};
s.src = ('https:' == d.location.protocol ? 'https://' : 'http://') + 'www.wufoo.com/scripts/embed/form.js';
s.onload = s.onreadystatechange = function() {
    var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return;
    try { " . $formhash . " = new WufooForm();" . $formhash . ".initialize(options);" . $formhash . ".display(); } catch (e) {}};
    var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);
})(document, 'script');";

        $id = 'Wufoo-form-' . $this->configurationManager->getContentObject()->data['uid'] . '-' . $this->configurationManager->getContentObject()->data['sys_language_uid'];
        $pr = $this->getPageRenderer()->addJsFooterInlineCode($id, $js);

        $this->view->assignMultiple([
            'username' => $username,
            'formhash' => $formhash,
            'height' => $height
        ]);
    }

    /**
     * @return \TYPO3\CMS\Core\Page\PageRenderer
     */
    protected function getPageRenderer()
    {
        if (version_compare(TYPO3_version, '7', '<')) {
            return $GLOBALS['TSFE']->getPageRenderer();
        }
        return $this->objectManager->get(PageRenderer::class);
    }
}
