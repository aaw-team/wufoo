.. include:: ../Includes.txt


.. _section-configuration:

Configuration Reference
=======================

There is no stable typoscript configuration for this plugin (yet). For the
typoscript configuration of the template paths see :ref:`section-templating`.

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
