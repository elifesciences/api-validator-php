#!/usr/bin/env bash
set -e

vendor/bin/phpcs --standard=phpcs.xml.dist --warning-severity=0 -p spec/ src/ test/
vendor/bin/phpunit --log-junit "build/${dependencies}-phpunit.xml"
(vendor/bin/phpspec run --format=junit | tee "build/${dependencies}-phpspec.xml") && echo "PHPSpec tests passed - see build/${dependencies}-phpspec.xml log"
