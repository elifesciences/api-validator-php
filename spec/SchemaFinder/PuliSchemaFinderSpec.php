<?php

namespace spec\eLife\ApiValidator\SchemaFinder;

use eLife\ApiValidator\MediaType;
use eLife\ApiValidator\SchemaFinder;
use PhpSpec\ObjectBehavior;
use Puli\Repository\InMemoryRepository;
use Puli\Repository\Resource\DirectoryResource;

final class PuliSchemaFinderSpec extends ObjectBehavior
{
    private $resourceRepository;

    public function let()
    {
        $this->resourceRepository = new InMemoryRepository();

        $this->resourceRepository->add('/elife/api/model', new DirectoryResource(__DIR__.'/../../test/resources'));

        $this->beConstructedWith($this->resourceRepository);
    }

    public function it_is_a_schema_finder()
    {
        $this->shouldImplement(SchemaFinder::class);
    }

    public function it_finds_a_schema_for_a_media_type()
    {
        $mediaType = MediaType::fromString('application/vnd.elife.person+json; version=1');

        $this->findSchemaFor($mediaType)->shouldBeLike(__DIR__.'/../../test/resources/person.v1.json');
    }
}
