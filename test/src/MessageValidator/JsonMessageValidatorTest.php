<?php

namespace test\eLife\ApiValidator\MessageValidator;

use eLife\ApiValidator\Exception\InvalidMessage;
use eLife\ApiValidator\MessageValidator\JsonMessageValidator;
use eLife\ApiValidator\SchemaFinder;
use eLife\ApiValidator\SchemaFinder\PuliSchemaFinder;
use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_TestCase;
use Psr\Http\Message\MessageInterface;
use Puli\Repository\InMemoryRepository;
use Puli\Repository\Resource\DirectoryResource;
use Webmozart\Json\JsonDecoder;

final class JsonMessageValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SchemaFinder
     */
    private $schemaFinder;

    /**
     * @var JsonDecoder
     */
    private $jsonDecoder;

    /**
     * @before
     */
    public function createSchemaFinder()
    {
        $resourceRepository = new InMemoryRepository();

        $resourceRepository->add(
            '/elife/api/model',
            new DirectoryResource(__DIR__.'/../../resources')
        );

        $this->schemaFinder = new PuliSchemaFinder($resourceRepository);
        $this->jsonDecoder = new JsonDecoder();
    }

    /**
     * @test
     * @dataProvider invalidJsonMessageProvider()
     */
    public function it_should_fail_on_an_invalid_json_message(MessageInterface $message)
    {
        $messageValidator = new JsonMessageValidator($this->schemaFinder, $this->jsonDecoder);

        $this->expectException(InvalidMessage::class);

        $messageValidator->validate($message);
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
