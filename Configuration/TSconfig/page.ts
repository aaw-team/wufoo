/**
 * Register plugin in new content element wizard 
 */
mod.wizards.newContentElement.wizardItems.plugins {
    elements {
        wufoo_form {
            iconIdentifier = content-wufoo
            title = LLL:EXT:wufoo/Resources/Private/Language/backend.xlf:plugin.title
            description = LLL:EXT:wufoo/Resources/Private/Language/backend.xlf:plugin.description
            tt_content_defValues {
                CType = list
                list_type = wufoo_form
            }
        }
    }
}
