<?php

namespace test\eLife\ApiValidator\SchemaFinder;

use eLife\ApiValidator\Exception\SchemaNotFound;
use eLife\ApiValidator\MediaType;
use eLife\ApiValidator\SchemaFinder\PuliSchemaFinder;
use PHPUnit_Framework_TestCase;
use Puli\Repository\Api\ResourceRepository;
use Puli\Repository\InMemoryRepository;
use Puli\Repository\Resource\DirectoryResource;

final class PuliSchemaFinderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var ResourceRepository
     */
    private $resourceRepository;

    /**
     * @before
     */
    public function createResourceRepository()
    {
        $this->resourceRepository = new InMemoryRepository();

        $this->resourceRepository->add(
            '/elife/api/model',
            new DirectoryResource(__DIR__.'/../../resources')
        );
    }

    /**
     * @test
     */
    public function it_finds_the_right_version()
    {
        $mediaType = MediaType::fromString('application/vnd.elife.person+json; version=2');

        $finder = new PuliSchemaFinder($this->resourceRepository);

        $this->assertSame(__DIR__.'/../../resources/person.v2.json', $finder->findSchemaFor($mediaType));
    }

    /**
     * @test
     */
    public function it_fails_if_the_version_is_not_found()
    {
        $mediaType = MediaType::fromString('application/vnd.elife.person+json; version=3');

        $finder = new PuliSchemaFinder($this->resourceRepository);

        $this->expectException(SchemaNotFound::class);

        $finder->findSchemaFor($mediaType);
    }

    /**
     * @test
     */
    public function it_fails_if_the_schema_is_not_found()
    {
        $mediaType = MediaType::fromString('application/vnd.elife.foo+json; version=1');

        $finder = new PuliSchemaFinder($this->resourceRepository);

        $this->expectException(SchemaNotFound::class);

        $finder->findSchemaFor($mediaType);
    }

    /**
     * @test
     */
    public function it_finds_the_error_schema()
    {
        $mediaType = MediaType::fromString('application/problem+json');

        $finder = new PuliSchemaFinder($this->resourceRepository);

        $this->assertSame(__DIR__.'/../../resources/error.v1.json', $finder->findSchemaFor($mediaType));
    }
}
