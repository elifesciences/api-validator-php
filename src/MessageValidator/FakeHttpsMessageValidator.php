<?php

namespace eLife\ApiValidator\MessageValidator;

use eLife\ApiValidator\MessageValidator;
use Exception;
use Psr\Http\Message\MessageInterface;
use Webmozart\Json\JsonDecoder;
use function GuzzleHttp\Psr7\stream_for;

/**
 * The eLife API expects assets to be served over HTTPS. When testing this
 * isn't always practical, so this validator will rewrite a message pretending
 * that all references to 'http://' are to 'https://'.
 */
final class FakeHttpsMessageValidator implements MessageValidator
{
    private $messageValidator;
    private $jsonDecoder;

    public function __construct(MessageValidator $messageValidator, JsonDecoder $jsonDecoder)
    {
        $this->messageValidator = $messageValidator;
        $this->jsonDecoder = $jsonDecoder;
    }

    public function validate(MessageInterface $message)
    {
        try {
            $this->jsonDecoder->decode($message->getBody());
            $message = $message->withBody(stream_for(str_replace('http:\/\/', 'https:\/\/', $message->getBody())));
        } catch (Exception $e) {
            // Do nothing.
        }

        $this->messageValidator->validate($message);
    }
}
