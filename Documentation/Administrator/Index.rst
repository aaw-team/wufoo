.. include:: ../Includes.txt


.. _section-admin-manual:

Administrator Manual
====================

.. _section-installation:

Installation
------------

Simply install the extension in TYPO3 Extension Manager or via composer:

``composer require aaw-team/wufoo``

.. _section-templating:

Templating
----------

Templating is done using fluid. The known rules apply for overriding layouts,
templates and partials:

.. code-block:: typoscript

    plugin.tx_wufoo {
        view {
            layoutRootPaths.10 = Path/To/My/Layouts
            partialRootPaths.10 = Path/To/My/Partials
            templateRootPaths.10 = Path/To/My/Templates
        }
    }

.. important::

    At the moment, the extension provides only one single template. There are no
    partials or layouts, because the work for the template is marginal.
