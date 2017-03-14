<?php

namespace eLife\ApiValidator\SchemaFinder;

use eLife\ApiValidator\Exception\SchemaNotFound;
use eLife\ApiValidator\MediaType;
use eLife\ApiValidator\SchemaFinder;

final class PathBasedSchemaFinder implements SchemaFinder
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = rtrim($path, '/');
    }

    public function findSchemaFor(MediaType $mediaType) : string
    {
        if ('application/problem+json' === $mediaType->getType()) {
            return $this->getResourcePath($mediaType, '/error.v1.json');
        }

        if (
            $mediaType->matchesType('~application/vnd.elife.([a-z-]+)\+json~', $type)
            &&
            $mediaType->hasParameter('version')
        ) {
            $resource = '/'.$type[1].'.v'.$mediaType->getParameter('version').'.json';
        } else {
            throw new SchemaNotFound($mediaType);
        }

        return $this->getResourcePath($mediaType, $resource);
    }

    private function getResourcePath(MediaType $mediaType, string $path) : string
    {
        $path = realpath($this->path.$path);

        if (!is_readable($path)) {
            throw new SchemaNotFound($mediaType);
        }

        return $path;
    }
}
