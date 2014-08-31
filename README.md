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

Brick\Application
-----------------

The central component to build a MVC application. Handles a `Request` and returns a `Response` that can be sent back to the browser.

Brick\Controller
----------------

Although a controller can be any class method or function, this component provides an `AbstractController` and several other building blocks useful in most web applications.

Brick\DateTime
--------------

A powerful set of immutable classes to work with dates and times.
Although PHP has a native `DateTime` class, it lacks many simple concepts like `LocalDate`, `LocalTime`, etc.

The classes follow the ISO 8601 standard for representing date and time objects.
They offer up to a nanosecond precision, where the native API has a 1 second precision.

The date-time API also offers a configurable `Clock` that you can set in your automated tests.

This component follows an important part of the JSR 310 (Date and Time API) specification from Java.
Don't expect an exact match of class and method names though, as a number of differences exist for technical or practical reasons.

Brick\DependencyInjection
-------------------------

A simple yet powerful Inversion Of Control framework.
It can be coupled to an `Application` to automatically inject required dependencies in controllers.

Brick\Event
-----------

A simple event dispatching/listening mechanism.

Brick\FileSystem
----------------

A collection of OO tools to interact with the file system.

Brick\Form
----------

A component to create, filter and validate web forms.

Brick\Geo
---------

A collection of classes to work with GIS geometries.

Brick\Http
----------

A collection of classes to represent HTTP requests and responses. This is a building block for `Application`.

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

Brick\Reflection
----------------

A collection of low-level tools to extend the PHP reflection capabilities.

Brick\Routing
-------------

A building block of the application, this component handles the routing of a request to a controller.

Brick\Session
-------------

A powerful alternative to PHP native sessions, allowing synchronized and non-blocking read/write to individual session entries.

Brick\Translation
-----------------

Handles translation of texts for a multilingual web application.

Brick\Validation
----------------

Provides a `Validator` interface for validating strings, and a collection of validators. This is mostly used by the `Form` component, but can be used independently as well.

Brick\View
----------

Handles HTML views for a web application.
