#!/usr/bin/env bash
set -e

composer update --no-interaction
proofreader spec/ src/ test/
vendor/bin/phpunit --log-junit build/phpunit.xml
(vendor/bin/phpspec run --format=junit | tee build/phpspec.xml) && echo "PHPSpec tests passed - see build/phpspec.xml log"
