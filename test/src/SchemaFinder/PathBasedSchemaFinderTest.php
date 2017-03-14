<?php

namespace test\eLife\ApiValidator\SchemaFinder;

use eLife\ApiValidator\Exception\SchemaNotFound;
use eLife\ApiValidator\MediaType;
use eLife\ApiValidator\SchemaFinder\PathBasedSchemaFinder;
use PHPUnit_Framework_TestCase;

final class PathBasedSchemaFinderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PathBasedSchemaFinder
     */
    private $schemaFinder;

    /**
     * @before
     */
    public function setUpFinder()
    {
        $this->schemaFinder = new PathBasedSchemaFinder(__DIR__.'/../../resources/');
    }

    /**
     * @test
     */
    public function it_finds_the_right_version()
    {
        $mediaType = MediaType::fromString('application/vnd.elife.person+json; version=2');

        $this->assertSame(realpath(__DIR__.'/../../resources/person.v2.json'), $this->schemaFinder->findSchemaFor($mediaType));
    }

    /**
     * @test
     */
    public function it_fails_if_the_version_is_not_found()
    {
        $mediaType = MediaType::fromString('application/vnd.elife.person+json; version=3');

        $this->expectException(SchemaNotFound::class);

        $this->schemaFinder->findSchemaFor($mediaType);
    }

    /**
     * @test
     */
    public function it_fails_if_the_schema_is_not_found()
    {
        $mediaType = MediaType::fromString('application/vnd.elife.foo+json; version=1');

        $this->expectException(SchemaNotFound::class);

        $this->schemaFinder->findSchemaFor($mediaType);
    }

    /**
     * @test
     */
    public function it_finds_the_error_schema()
    {
        $mediaType = MediaType::fromString('application/problem+json');

        $this->assertSame(realpath(__DIR__.'/../../resources/error.v1.json'), $this->schemaFinder->findSchemaFor($mediaType));
    }
}
