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

// Register the plugin
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin([
    'LLL Wufoo Forms',
    'wufoo_form',
//     'icon...'
], 'list_type', 'wufoo');

// Add flexform
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue('wufoo_form', 'FILE:EXT:wufoo/Configuration/Flexform/PluginWufoo.xml');
// show tt_content.pi_flexform when the plugin is shown
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['wufoo_form'] = 'pi_flexform';
// disable some fields when the plugin is shown
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['wufoo_form'] = 'select_key,pages,recursive';
