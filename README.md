## Brick

<img src="https://raw.githubusercontent.com/brick/brick/master/logo.png" alt="" align="left" height="64">

Incubator for PHP components under development.

[![Build Status](https://travis-ci.org/brick/brick.svg?branch=master)](https://travis-ci.org/brick/brick)
[![Coverage Status](https://coveralls.io/repos/brick/brick/badge.svg?branch=master)](https://coveralls.io/r/brick/brick)

Once a component reaches sufficient maturity, it will be moved to its [own repository](https://github.com/brick)
and composer package, and enter a beta phase. For this reason, this repository will never contain releases.

The incubator can be included in your project using [Composer](https://getcomposer.org/).
Just define the following requirement in your `composer.json` file:

    {
        "require": {
            "brick/brick": "dev-master"
        }
    }

Feel free to use the incubator for your own use/research, but please note that the API can, and will,
change at any time. Components can also disappear at any time.

This does not mean that you cannot use this repository in your project if you find it useful;
just be sure to lock Composer to the specific version your code is written for:

    {
        "require": {
            "brick/brick": "dev-master#f68f91a5a80c6dc4a91923800e5eaf5acbd1314b"
        }
    }

Or just avoid blind `composer update` runs once your `composer.lock` file has been written.
Update individual components instead when required, and only update `brick/brick` if needed.

---

### Brick\Db

Tools for database interaction.

### Brick\Doctrine

Doctrine type mappings and functions for Brick objects.

### Brick\FileStorage

A generic interface for file storage, with file system and Amazon S3 implementations.

### Brick\FileSystem

A collection of OO tools to interact with the file system.

### Brick\Locale

A collection of classes to handle countries, languages and currencies.

### Brick\Session

A powerful alternative to PHP native sessions, allowing synchronized and non-blocking read/write to individual session entries.

### Brick\Translation

Handles translation of texts for a multilingual web application.

### Brick\Validation

Provides a `Validator` interface for validating strings, and a collection of validators. This is mostly used by the `Form` component, but can be used independently as well.
