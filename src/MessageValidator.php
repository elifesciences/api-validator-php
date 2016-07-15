<?php

namespace eLife\ApiValidator;

use eLife\ApiValidator\Exception\InvalidMessage;
use Psr\Http\Message\MessageInterface;

interface MessageValidator
{
    /**
     * @throws InvalidMessage
     */
    public function validate(MessageInterface $message);
}
