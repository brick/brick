## Brick

<img src="https://raw.githubusercontent.com/brick/brick/master/logo.png" alt="" align="left" height="64">

Incubator for PHP components under development.

[![Build Status](https://github.com/brick/brick/workflows/CI/badge.svg)](https://github.com/brick/brick/actions)
[![Coverage Status](https://coveralls.io/repos/github/brick/brick/badge.svg?branch=master)](https://coveralls.io/github/brick/brick?branch=master)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](http://opensource.org/licenses/MIT)

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
