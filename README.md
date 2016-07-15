eLife API Validator for PHP
===========================

This library provides a validator for the [eLife Sciences API](https://github.com/elifesciences/api-raml).

It checks HTTP requests/responses to make sure that they match the specification. Currently only the body of the message is validated against the schema for that media type.

Dependencies
------------

* [Composer](https://getcomposer.org/)
* [Puli CLI](http://puli.io)
* PHP 7

Installation
------------

Execute `composer require elife/api-validator:dev-master`.

Usage
-----

To validate a message:

```php
use eLife\ApiValidator\MessageValidator\JsonMessageValidator;
use eLife\ApiValidator\SchemaFinder\PuliSchemaFinder;
use Webmozart\Json\JsonDecoder;

$messageValidator = new JsonMessageValidator(new PuliSchemaFinder($resourceRepository), new JsonDecoder());

$messageValidator->validate($message);
```
