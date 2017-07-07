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

use AawTeam\Wufoo\Utility\FormUrlUtility;
use AawTeam\Wufoo\Utility\LocalizationUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Form Controller
 *
 * @author Agentur am Wasser | Maeder & Partner AG (development@agenturamwasser.ch)
 * @package AawTeam\Wufoo\Controller
 */
class FormController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * Extend parent method: apply stdWrap to (therefor configured) options in
     * settings.
     *
     * {@inheritDoc}
     * @see \TYPO3\CMS\Extbase\Mvc\Controller\AbstractController::injectConfigurationManager()
     */
    public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager)
    {
        parent::injectConfigurationManager($configurationManager);

        if ($this->settings['useStdWrap']) {
            $stdWrapProperties = GeneralUtility::trimExplode(',', (string)$this->settings['useStdWrap'], true);
            if (!empty($stdWrapProperties)) {
                /** @var \TYPO3\CMS\Core\TypoScript\TypoScriptService $typoscriptService*/
                $typoscriptService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\TypoScript\TypoScriptService::class);
                $typoscriptSettings = $typoscriptService->convertPlainArrayToTypoScriptArray($this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS));

                foreach ($stdWrapProperties as $property) {
                    if (is_array($typoscriptSettings[$property . '.'])) {
                        $this->settings[$property] = $this->configurationManager->getContentObject()->stdWrapValue($property, $typoscriptSettings, $this->settings[$property]);
                    }
                }
            }
        }
    }

    /**
     * @return void|string
     */
    public function indexAction()
    {
        $formUrl = \trim($this->settings['formUrl']);
        if (!FormUrlUtility::verifyUrl($formUrl)) {
            return LocalizationUtility::translate('error.invalidFormUrl');
        }

        // Get the canonical form URL (experimental)
        if ($this->settings['useCanonicalFormUrl']) {
            try {
                $canonicalFormUrl = FormUrlUtility::getCanonicalUrl($formUrl);
                if ($formUrl !== $canonicalFormUrl && FormUrlUtility::verifyUrl($canonicalFormUrl)) {
                    $formUrl = $canonicalFormUrl;
                }
            } catch (\AawTeam\Wufoo\Exception\CanonicalUrlException $e) {}
        }

        // Extract data from $formUrl
        $formData = FormUrlUtility::extractData($formUrl);
        $username = $formData['username'];
        $formhash = $formData['formhash'];

        // Set height
        $height = 500;
        if (!$this->settings['autoresize'] && MathUtility::canBeInterpretedAsInteger($this->settings['height']) && $this->settings['height'] > 0) {
            $height = $this->settings['height'];
        }

        // Create the javascript
        $jsId = 'tx_wufoo_form_' . \sha1(
            $this->configurationManager->getContentObject()->data['uid'] .
            $this->configurationManager->getContentObject()->data['sys_language_uid'] .
            $formUrl
        );
        $js = "
var " . $jsId . ";(function(d, t) {
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
    try { " . $jsId . " = new WufooForm();" . $jsId . ".initialize(options);" . $jsId . ".display(); } catch (e) {}};
    var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);
})(document, 'script');";

        // Register javascript
        GeneralUtility::makeInstance(PageRenderer::class)->addJsFooterInlineCode($jsId, $js);

        $this->view->assignMultiple([
            'username' => $username,
            'formhash' => $formhash,
            'height' => $height
        ]);
    }
}
