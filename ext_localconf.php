<?php
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

defined ('TYPO3_MODE') or die ('Access denied.');

// Register plugin icon
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'content-wufoo',
    \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
    ['source' => 'EXT:wufoo/Resources/Public/Images/Plugin-32x32.png']
);

// Register page TSConfig
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:wufoo/Configuration/TSconfig/page.ts">');

// Configure plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('AawTeam.Wufoo', 'Form', [
    'Form' => 'index'
]);

// Add default typoscript setup
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup('
/**
 * Default plugin configuration
 */
plugin.tx_wufoo {
    settings {
        useStdWrap =
        formUrl =
        showHeader = 1
        autoresize = 1
        height = 500
    }
    view {
        layoutRootPaths.0 = EXT:wufoo/Resources/Private/Layouts
        partialRootPaths.0 = EXT:wufoo/Resources/Private/Partials
        templateRootPaths.0 = EXT:wufoo/Resources/Private/Templates
    }
}');

// Register canonicalUrl cache (StringFrontend is forced)
if (!\is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['wufoo_canonicalUrl'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['wufoo_canonicalUrl'] = [
        'options' => [
            'defaultLifetime' => 604800 // 7 days
        ],
        'groups' => ['pages']
    ];
} else {
    if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['wufoo_canonicalUrl']['backend'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['wufoo_canonicalUrl']['backend'] = \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class;
    }
    if (!isset($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['wufoo_canonicalUrl']['options']['defaultLifetime'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['wufoo_canonicalUrl']['options']['defaultLifetime'] = 604800;
    }
    if (!\is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['wufoo_canonicalUrl']['groups'])) {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['wufoo_canonicalUrl']['groups'] = ['pages'];
    }
}
$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['wufoo_canonicalUrl']['frontend'] = \TYPO3\CMS\Core\Cache\Frontend\StringFrontend::class;
