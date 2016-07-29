<?php

namespace eLife\ApiValidator;

use Assert\Assertion;
use InvalidArgumentException;
use function GuzzleHttp\Psr7\parse_header;

final class MediaType
{
    private $type;
    private $parameters = [];

    public function __construct(string $type, array $parameters = [])
    {
        Assertion::regex($type, '~^[\w.+-]+/[\w.+-]+$~');

        foreach ($parameters as $key => $value) {
            Assertion::string($value);

            $this->parameters[$key] = trim($value);
        }

        ksort($this->parameters);

        $this->type = $type;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromString(string $header)
    {
        Assertion::notBlank($header);

        $contentType = parse_header($header)[0];

        return new self(array_shift($contentType), $contentType);
    }

    public function __toString() : string
    {
        $parts = [$this->type];

        foreach ($this->parameters as $key => $value) {
            if (empty($value)) {
                $parts[] = [$key];
            } else {
                $parts[] = $key.'='.$value;
            }
        }

        return implode('; ', $parts);
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function matchesType(string $pattern, &$matches = []) : bool
    {
        return (bool) preg_match($pattern, $this->type, $matches);
    }

    /**
     * @return string[]
     */
    public function getParameters() : array
    {
        return $this->parameters;
    }

    /**
     * @return string|null
     */
    public function getParameter(string $key)
    {
        return $this->parameters[$key] ?? null;
    }

    public function hasParameter(string $key) : bool
    {
        return isset($this->parameters[$key]);
    }
}
