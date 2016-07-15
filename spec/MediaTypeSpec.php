<?php

namespace spec\eLife\ApiValidator;

use PhpSpec\ObjectBehavior;

final class MediaTypeSpec extends ObjectBehavior
{
    private $type;

    public function let()
    {
        $this->type = 'application/vnd.elife.labs-experiment+json';

        $this->beConstructedWith($this->type);
    }

    public function it_can_become_a_string()
    {
        $this->__toString()->shouldBe($this->type);
    }

    public function it_has_a_media_type()
    {
        $this->getType()->shouldBe($this->type);
    }

    public function it_can_not_have_parameters()
    {
        $this->getParameters()->shouldBe([]);
    }

    public function it_can_have_parameters()
    {
        $parameters = ['foo' => 'bar'];

        $this->beConstructedWith($this->type, $parameters);

        $this->getParameters()->shouldBe($parameters);
    }

    public function it_can_be_constructed_from_a_string()
    {
        $this->beConstructedThrough('fromString', [$this->type.';foo=bar;baz=qux']);

        $this->getType()->shouldBe($this->type);
        $this->getParameters()->shouldBe(['baz' => 'qux', 'foo' => 'bar']);
    }
}
