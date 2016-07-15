<?php

namespace spec\eLife\ApiValidator\Exception;

use Exception;
use PhpSpec\ObjectBehavior;
use RuntimeException;

final class InvalidMessageSpec extends ObjectBehavior
{
    private $message;
    private $request;

    public function let()
    {
        $this->message = 'foo';

        $this->beConstructedWith($this->message, $this->request);
    }

    public function it_has_a_message()
    {
        $this->getMessage()->shouldBe($this->message);
    }

    public function it_can_not_have_a_previous_exception()
    {
        $this->getPrevious()->shouldBe(null);
    }

    public function it_can_have_a_previous_exception(Exception $previous)
    {
        $this->beConstructedWith($this->message, $previous);

        $this->getPrevious()->shouldBeLike($previous);
    }

    public function it_is_a_runtime_exception()
    {
        $this->shouldHaveType(RuntimeException::class);
    }
}
