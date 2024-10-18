<?php

namespace eLife\ApiValidator\MessageValidator;

use eLife\ApiValidator\MessageValidator;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\MessageInterface;

/**
 * The eLife API expects assets to be served over HTTPS. When testing this
 * isn't always practical, so this validator will rewrite a message pretending
 * that all references to 'http://' are to 'https://'.
 */
final class FakeHttpsMessageValidator implements MessageValidator
{
    private $messageValidator;

    public function __construct(MessageValidator $messageValidator)
    {
        $this->messageValidator = $messageValidator;
    }

    public function validate(MessageInterface $message)
    {
        json_decode($message->getBody());

        if (JSON_ERROR_NONE === json_last_error()) {
            $message = $message->withBody(Utils::streamFor(str_replace('"http:', '"https:', $message->getBody())));
        }

        $this->messageValidator->validate($message);
    }
}
