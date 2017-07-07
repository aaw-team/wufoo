.. include:: ../Includes.txt


.. _section-configuration:

Configuration Reference
=======================

For the typoscript configuration of the template paths see
:ref:`section-templating`.

.. _section-configuration-settings:

Settings
--------

These options are to be configured in `plugin.tx_wufoo.settings`.

.. important::

   Be aware: if you use the plugin in tt_content, these settings come from
   FlexForms configuration. But you can use them when you insert a plugin with
   Typoscript, see :ref:`section-configuration-add-plugin-with-typoscript`.

.. container:: table-row

   Property
         formUrl

   Data type
         string

   Description
         The URL of the form to display.


.. container:: table-row

   Property
         showHeader

   Data type
         bool

   Description
         Whether the header of the form should be displayed.
         Default: 1


.. container:: table-row

   Property
         autoresize

   Data type
         bool

   Description
         Whether the form should be resized automatically.
         Default: 1


.. container:: table-row

   Property
         height

   Data type
         int

   Description
         The height of the form in pixels. This value is set dynamically by the
         wufoo's javascript if `autoresize` is enabled. Though, the `<iframe>`
         in the `<noscript>` always uses it.
         Default: 500


.. container:: table-row

   Property
         useStdWrap

   Data type
         list (comma separated strings)

   Description
         Apply stdWrap to the options (from `plugin.tx_wufoo.settings`) in this
         list.

         **Example**: assume, you add a field `myFormUrlField` to `pages` which
         holds the URL of the form that should be displayed at the bottom of
         this page (when set):

         .. code-block:: typoscript

             page.100 = COA
             page.100 {
                 if.isTrue.field = myFormUrlField
                 10 = < tt_content.list.20.wufoo_form
                 10.settings {
                     useStdWrap := addToList(formUrl)
                     formUrl.data = page:myFormUrlField
                 }
             }


.. _section-configuration-add-plugin-with-typoscript:

Add a form by TypoScript
------------------------

To add a form by Typoscript simply use something like this (replace `[URL]` with
the actual form URL of course):

.. code-block:: typoscript

    lib.wufooform = < tt_content.list.20.wufoo_form
    lib.wufooform.settings.formUrl = [URL]


Experimental feature "Use canonical form URL"
---------------------------------------------

.. important::

   This feature is still experimental and can be changed or removed without
   further notice!

.. code-block:: html

    plugin.tx_wufoo.settings.useCanonicalFormUrl = 1


With this feature enabled, the extension tries to fetch the form URL and search
the received HTML contents for a canonical URL (using `PHP's DOM extension
<https://secure.php.net/manual/en/book.dom.php>`_):

.. code-block:: html

    <link rel="canonical" href="...">

When a valid canonical URL is found, it will be used as form URL. To decrease
the data traffic and execution time, a cache is used to store the canonical
URL's.
