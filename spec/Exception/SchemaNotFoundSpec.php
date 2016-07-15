<?php

namespace spec\eLife\ApiValidator\Exception;

use eLife\ApiValidator\MediaType;
use Exception;
use PhpSpec\ObjectBehavior;
use RuntimeException;

final class SchemaNotFoundSpec extends ObjectBehavior
{
    private $mediaType;

    public function let()
    {
        $this->mediaType = MediaType::fromString('application/json; foo=bar');

        $this->beConstructedWith($this->mediaType);
    }

    public function it_has_a_message()
    {
        $this->getMessage()->shouldBe('Could not find schema for '.$this->mediaType);
    }

    public function it_has_a_media_type()
    {
        $this->getMediaType()->shouldBeLike($this->mediaType);
    }

    public function it_can_not_have_a_previous_exception()
    {
        $this->getPrevious()->shouldBe(null);
    }

    public function it_can_have_a_previous_exception(Exception $previous)
    {
        $this->beConstructedWith($this->mediaType, $previous);

        $this->getPrevious()->shouldBeLike($previous);
    }

    public function it_is_a_runtime_exception()
    {
        $this->shouldHaveType(RuntimeException::class);
    }
}
