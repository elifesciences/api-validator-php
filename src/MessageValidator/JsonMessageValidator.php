<?php

namespace eLife\ApiValidator\MessageValidator;

use eLife\ApiValidator\Exception\InvalidMessage;
use eLife\ApiValidator\MediaType;
use eLife\ApiValidator\MessageValidator;
use eLife\ApiValidator\SchemaFinder;
use InvalidArgumentException;
use JsonSchema\Validator;
use Psr\Http\Message\MessageInterface;

final class JsonMessageValidator implements MessageValidator
{
    private $schemaFinder;
    private $validator;

    public function __construct(SchemaFinder $schemaFinder, Validator $validator)
    {
        $this->schemaFinder = $schemaFinder;
        $this->validator = $validator;
    }

    public function validate(MessageInterface $message)
    {
        if (empty($body = $message->getBody()->__toString()) && !empty($message->getHeaderLine('Content-Type'))) {
            throw new InvalidMessage('Message has a Content-Type header but no body');
        } elseif (!empty($body = $message->getBody()->__toString()) && empty($message->hasHeader('Content-Type'))) {
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

        if (false === $mediaType->matchesType('~application\/([a-z-\.]*\+)json~')) {
            return;
        }

        $schema = $this->schemaFinder->findSchemaFor($mediaType);

        $this->validator->reset();
        $this->validator->check(json_decode($message->getBody()), (object) ['$ref' => 'file://'.realpath($schema)]);

        if (!$this->validator->isValid()) {
            $message = "JSON does not validate. Violations:\n";
            foreach ($this->validator->getErrors() as $error) {
                $message .= sprintf("[%s] %s\n", $error['property'], $error['message']);
            }

            $this->validator->reset();

            throw new InvalidMessage($message);
        }

        $this->validator->reset();
    }
}
