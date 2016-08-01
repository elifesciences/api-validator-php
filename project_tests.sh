#!/usr/bin/env bash
set -e

: "${dependencies:?Need to set dependencies environment variable}"
if [ "$dependencies" = "lowest" ]; then composer update --prefer-lowest --no-interaction; else composer update --no-interaction; fi;

if [ "$dependencies" = "lowest" ]; then proofreader spec/ src/ test/; fi;
vendor/bin/phpunit --log-junit "build/${dependencies}-phpunit.xml"
(vendor/bin/phpspec run --format=junit | tee "build/${dependencies}-phpspec.xml") && echo "PHPSpec tests passed - see build/${dependencies}-phpspec.xml log"
