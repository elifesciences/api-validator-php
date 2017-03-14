<?php

namespace spec\eLife\ApiValidator\MessageValidator;

use eLife\ApiValidator\MessageValidator;
use GuzzleHttp\Psr7\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Http\Message\MessageInterface;
use function GuzzleHttp\Psr7\str;

final class FakeHttpsMessageValidatorSpec extends ObjectBehavior
{
    private $messageValidator;

    public function let(MessageValidator $messageValidator)
    {
        $this->messageValidator = $messageValidator;

        $this->beConstructedWith($messageValidator);
    }

    public function it_is_a_message_validator()
    {
        $this->shouldImplement(MessageValidator::class);
    }

    public function it_should_rewrite_json()
    {
        $request = new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['foo' => 'http://www.example.com/', 'bar' => 'baz http://www.example.com/'])
        );

        $this->messageValidator->validate(Argument::that(function (MessageInterface $message) {
            return str($message) === str(new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode(['foo' => 'https://www.example.com/', 'bar' => 'baz http://www.example.com/'])
            ));
        }))->shouldBeCalled();

        $this->validate($request);
    }

    public function it_should_not_touch_non_json()
    {
        $request = new Response(
            200,
            ['Content-Type' => 'text/plain'],
            'foo http://www.example.com/'
        );

        $this->messageValidator->validate(Argument::that(function (MessageInterface $message) {
            return str($message) === str(new Response(
                200,
                ['Content-Type' => 'text/plain'],
                'foo http://www.example.com/'
            ));
        }))->shouldBeCalled();

        $this->validate($request);
    }
}
