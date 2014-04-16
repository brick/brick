Brick
=====

PHP 5.5+ framework under development.

You're free to use this framework for your own use/research, but please note that the API can, and will, change at any time. Following is a non-exhaustive component overview.

Brick\Application
-----------------

The central component to build a MVC application. Handles a `Request` and returns a `Response` that can be sent back to the browser.

Brick\Controller
----------------

Although a controller can be any class method or function, this component provides an `AbstractController` and several other building blocks useful in most web applications.

Brick\DateTime
--------------

A powerful set of immutable classes to handle dates and times. Although PHP has a native `DateTime` class, it misses several simple concepts like an isolated date or time, known as `LocalDate` and `LocalTime`, or a date/time without a time zone, known as `LocalDateTime`.

This component is heavily inspired by the JSR-310 API from Java.

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

Brick\Math
----------

Provides a `Decimal` class to work with arbitrary precision decimal numbers.

Brick\Money
-----------

Handles exact monetary calculations.

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
