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

// Register page TSConfig (new content element wizard)
if (version_compare(TYPO3_version, '7', '>=')) {
    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
            'content-wufoo',
            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
            ['source' => 'EXT:wufoo/Resources/Public/Images/Plugin-32x32.png']
            );
    $iconLine = 'iconIdentifier = content-wufoo';
} else {
    $iconLine = 'icon = ../typo3conf/ext/wufoo/Resources/Public/Images/Plugin.png';
}
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
mod.wizards.newContentElement.wizardItems.plugins {
    elements {
        wufoo_form {
            ' . $iconLine . '
            title = LLL:EXT:wufoo/Resources/Private/Language/backend.xlf:plugin.title
            description = LLL:EXT:wufoo/Resources/Private/Language/backend.xlf:plugin.description
            tt_content_defValues {
                CType = list
                list_type = wufoo_form
            }
        }
    }
}');

// Configure plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('AawTeam.Wufoo', 'Form', [
    'Form' => 'index'
]);
