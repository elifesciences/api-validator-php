<?php

namespace eLife\ApiValidator;

use eLife\ApiValidator\Exception\SchemaNotFound;

interface SchemaFinder
{
    /**
     * @throws SchemaNotFound
     */
    public function findSchemaFor(MediaType $mediaType) : string;
}
