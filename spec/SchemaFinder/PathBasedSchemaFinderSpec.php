<?php

namespace spec\eLife\ApiValidator\SchemaFinder;

use eLife\ApiValidator\MediaType;
use eLife\ApiValidator\SchemaFinder;
use PhpSpec\ObjectBehavior;

final class PathBasedSchemaFinderSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(__DIR__.'/../../test/resources');
    }

    public function it_is_a_schema_finder()
    {
        $this->shouldImplement(SchemaFinder::class);
    }

    public function it_finds_a_schema_for_a_media_type()
    {
        $mediaType = MediaType::fromString('application/vnd.elife.person+json; version=1');

        $this->findSchemaFor($mediaType)->shouldBeLike(realpath(__DIR__.'/../../test/resources/person.v1.json'));
    }
}
