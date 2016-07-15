<?php

namespace eLife\ApiValidator\Exception;

use eLife\ApiValidator\MediaType;
use Exception;
use RuntimeException;

class SchemaNotFound extends RuntimeException
{
    private $mediaType;

    public function __construct(MediaType $mediaType, Exception $previous = null)
    {
        parent::__construct('Could not find schema for '.$mediaType, 0, $previous);

        $this->mediaType = $mediaType;
    }

    final public function getMediaType() : MediaType
    {
        return $this->mediaType;
    }
}
