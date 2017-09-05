<?php

namespace spec\eLife\ApiValidator\MessageValidator;

use eLife\ApiValidator\Exception\InvalidMessage;
use eLife\ApiValidator\MediaType;
use eLife\ApiValidator\MessageValidator;
use eLife\ApiValidator\SchemaFinder;
use GuzzleHttp\Psr7\Response;
use JsonSchema\Validator;
use PhpSpec\ObjectBehavior;

final class JsonMessageValidatorSpec extends ObjectBehavior
{
    private $schemaFinder;
    private $validator;

    public function let(SchemaFinder $schemaFinder)
    {
        $this->schemaFinder = $schemaFinder;
        $this->validator = new Validator();

        $this->schemaFinder->findSchemaFor(MediaType::fromString('application/vnd.elife.person+json; version=1'))
            ->willReturn(__DIR__.'/../../test/resources/person.v1.json');

        $this->beConstructedWith($schemaFinder, $this->validator);
    }

    public function it_is_a_message_validator()
    {
        $this->shouldImplement(MessageValidator::class);
    }

    public function it_should_validate_a_json_message()
    {
        $request = new Response(
            200,
            ['Content-Type' => 'application/vnd.elife.person+json; version=1'],
            json_encode(['firstName' => 'foo', 'lastName' => 'bar'])
        );

        $this->validate($request);
    }

    public function it_should_fail_on_an_invalid_json_message()
    {
        $request = new Response(
            200,
            ['Content-Type' => 'application/vnd.elife.person+json; version=1'],
            json_encode(['firstName' => 'foo', 'lastName' => 'bar', 'age' => 'baz'])
        );

        $this->shouldThrow(InvalidMessage::class)->duringValidate($request);
    }

    public function it_ignores_non_json_messages()
    {
        $request = new Response(
            200,
            ['Content-Type' => 'application/vnd.elife.person+xml; version=1'],
            'foo'
        );

        $this->validate($request);
    }

    public function it_ignores_plain_json_messages()
    {
        $request = new Response(
            200,
            ['Content-Type' => 'application/json'],
            'foo'
        );

        $this->validate($request);
    }
}
