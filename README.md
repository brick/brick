Brick
=====

PHP 5.5+ framework under development.

This is the main incubator for components under development.
Once a component reaches sufficient maturity, it will be moved to its own repository and composer package, and enter a beta phase.

The incubator can be included in your project using [Composer](https://getcomposer.org/). Just define the following requirement in your `composer.json` file:

    {
        "require": {
            "brick/brick": "dev-master"
        }
    }

Feel free to use the incubator for your own use/research, but please note that the API can, and will, change at any time.

---

Brick\FileSystem
----------------

A collection of OO tools to interact with the file system.

Brick\Form
----------

A component to create, filter and validate web forms.

Brick\Locale
------------

A collection of classes to handle countries, languages and currencies.

Brick\Money
-----------

Handles exact monetary calculations.

Working with financial data is a serious matter, and small rounding mistakes in an application may lead to disastrous
consequences in real life. That's why floating-point arithmetic is not suited for monetary calculations.

This component is based on the `Math` component and handles exact calculations on arbitrary-precision monies.

Brick\PhoneNumber
-----------------

Handles and validates international phone numbers.

Brick\Session
-------------

A powerful alternative to PHP native sessions, allowing synchronized and non-blocking read/write to individual session entries.

Brick\Translation
-----------------

Handles translation of texts for a multilingual web application.

Brick\Validation
----------------

Provides a `Validator` interface for validating strings, and a collection of validators. This is mostly used by the `Form` component, but can be used independently as well.
