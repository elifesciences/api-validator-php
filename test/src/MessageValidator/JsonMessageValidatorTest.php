<?php

namespace test\eLife\ApiValidator\MessageValidator;

use eLife\ApiValidator\Exception\InvalidMessage;
use eLife\ApiValidator\MessageValidator\JsonMessageValidator;
use eLife\ApiValidator\SchemaFinder\PathBasedSchemaFinder;
use GuzzleHttp\Psr7\Response;
use JsonSchema\Validator;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\MessageInterface;

final class JsonMessageValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var JsonMessageValidator
     */
    private $messageValidator;

    /**
     * @before
     */
    public function createMessageValidator()
    {
        $this->messageValidator = new JsonMessageValidator(
            new PathBasedSchemaFinder(__DIR__.'/../../resources/'),
            new Validator()
        );
    }

    /**
     * @test
     * @dataProvider invalidJsonMessageProvider()
     */
    public function it_should_fail_on_an_invalid_json_message(MessageInterface $message)
    {
        $this->expectException(InvalidMessage::class);

        $this->messageValidator->validate($message);
    }

    public function invalidJsonMessageProvider()
    {
        return [
            'non-JSON body' => [
                new Response(
                    200,
                    ['Content-Type' => 'application/vnd.elife.person+json; version=1'],
                    'foo'
                ),
            ],
            'missing Content-Type header' => [
                new Response(
                    200,
                    [],
                    json_encode(['firstName' => 'foo', 'lastName' => 'bar', 'age' => 'baz'])
                ),
            ],
            'missing body' => [
                new Response(
                    200,
                    ['Content-Type' => 'application/vnd.elife.person+json; version=1']
                ),
            ],
        ];
    }
}
