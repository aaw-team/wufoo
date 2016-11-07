.. include:: ../Includes.txt


.. _section-admin-manual:

Administrator Manual
====================

.. _section-installation:

Installation
------------

Simply install the extension in Extension Manager.


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

    It is recommended to work with the **layout only**, because the template
    itself may change over time. There is nothing special to change in the
    template anyway, there is only a ``<div>`` and a ``<noscript>`` tag.

