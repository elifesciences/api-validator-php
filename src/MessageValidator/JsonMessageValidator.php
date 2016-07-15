<?php

namespace eLife\ApiValidator\MessageValidator;

use eLife\ApiValidator\Exception\InvalidMessage;
use eLife\ApiValidator\MediaType;
use eLife\ApiValidator\MessageValidator;
use eLife\ApiValidator\SchemaFinder;
use InvalidArgumentException;
use Psr\Http\Message\MessageInterface;
use Webmozart\Json\DecodingFailedException;
use Webmozart\Json\JsonDecoder;
use Webmozart\Json\ValidationFailedException;

final class JsonMessageValidator implements MessageValidator
{
    private $schemaFinder;
    private $jsonDecoder;

    public function __construct(SchemaFinder $schemaFinder, JsonDecoder $jsonDecoder)
    {
        $this->schemaFinder = $schemaFinder;
        $this->jsonDecoder = $jsonDecoder;
    }

    public function validate(MessageInterface $message)
    {
        if (empty($body = $message->getBody()->__toString()) && $message->hasHeader('Content-Type')) {
            throw new InvalidMessage('Message has a Content-Type header but no body');
        } elseif (!empty($body = $message->getBody()->__toString()) && !$message->hasHeader('Content-Type')) {
            throw new InvalidMessage('Message has a body but no Content-Type header');
        }

        if (empty($message->getHeaderLine('Content-Type'))) {
            return;
        }

        try {
            $mediaType = MediaType::fromString($message->getHeaderLine('Content-Type'));
        } catch (InvalidArgumentException $e) {
            throw new InvalidMessage('Message has invalid Content-Type header', $e);
        }

        if (false === $mediaType->matchesType('~application\/([a-z-\.]*\+)?json~')) {
            return;
        }

        $schema = $this->schemaFinder->findSchemaFor($mediaType);

        try {
            $this->jsonDecoder->decode($message->getBody(), $schema);
        } catch (DecodingFailedException $e) {
            throw new InvalidMessage($e->getMessage(), $e);
        } catch (ValidationFailedException $e) {
            throw new InvalidMessage($e->getMessage(), $e);
        }
    }
}
