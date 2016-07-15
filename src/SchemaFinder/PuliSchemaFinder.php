<?php

namespace eLife\ApiValidator\SchemaFinder;

use eLife\ApiValidator\Exception\SchemaNotFound;
use eLife\ApiValidator\MediaType;
use eLife\ApiValidator\SchemaFinder;
use Puli\Repository\Api\Resource\FilesystemResource;
use Puli\Repository\Api\ResourceNotFoundException;
use Puli\Repository\Api\ResourceRepository;

final class PuliSchemaFinder implements SchemaFinder
{
    private $resourceRepository;

    public function __construct(ResourceRepository $resourceRepository)
    {
        $this->resourceRepository = $resourceRepository;
    }

    public function findSchemaFor(MediaType $mediaType) : string
    {
        if ('application/problem+json' === $mediaType->getType()) {
            return $this->getResourcePath($mediaType, '/elife/api/model/error.json');
        }

        if (
            $mediaType->matchesType('~application/vnd.elife.([a-z-]+)\+json~', $type)
            &&
            $mediaType->hasParameter('version')
        ) {
            $resource = '/elife/api/model/'.$type[1].'.v'.$mediaType->getParameter('version').'.json';
        } else {
            throw new SchemaNotFound($mediaType);
        }

        return $this->getResourcePath($mediaType, $resource);
    }

    private function getResourcePath(MediaType $mediaType, string $path) : string
    {
        try {
            $resource = $this->resourceRepository->get($path);

            if (false === $resource instanceof FilesystemResource) {
                throw new ResourceNotFoundException($resource->getPath().' is not a filesystem resource');
            }
        } catch (ResourceNotFoundException $e) {
            throw new SchemaNotFound($mediaType, $e);
        }

        return $resource->getFilesystemPath();
    }
}
