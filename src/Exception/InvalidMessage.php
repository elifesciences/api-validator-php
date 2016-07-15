<?php

namespace eLife\ApiValidator\Exception;

use Exception;
use RuntimeException;

class InvalidMessage extends RuntimeException
{
    public function __construct($message = '', Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
