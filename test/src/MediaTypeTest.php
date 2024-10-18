<?php

namespace test\eLife\ApiValidator;

use eLife\ApiValidator\MediaType;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TypeError;

final class MediaTypeTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidMediaTypeProvider
     */
    public function it_throws_an_exception_when_a_media_type_is_invalid($mediaType, string $expectedException)
    {
        $this->expectException($expectedException);

        new MediaType($mediaType);
    }

    public function invalidMediaTypeProvider()
    {
        return [
            'not a string' => [null, TypeError::class],
            'empty string' => ['', InvalidArgumentException::class],
            'missing second part' => ['text', InvalidArgumentException::class],
            'empty first part' => ['/json', InvalidArgumentException::class],
            'empty second part' => ['text/', InvalidArgumentException::class],
        ];
    }

    /**
     * @test
     */
    public function it_throws_an_exception_when_a_parameter_is_invalid()
    {
        $this->expectException(InvalidArgumentException::class);

        new MediaType('application/json', ['foo' => $this]);
    }

    /**
     * @test
     * @dataProvider invalidStringProvider
     */
    public function it_throws_an_exception_when_a_string_input_is_invalid($string, string $expectedException)
    {
        $this->expectException($expectedException);

        MediaType::fromString($string);
    }

    public function invalidStringProvider()
    {
        return [
            'not a string' => [null, TypeError::class],
            'empty string' => ['', InvalidArgumentException::class],
            'missing media type' => ['foo=bar', InvalidArgumentException::class],
            'invalid media type' => ['application/; foo=bar', InvalidArgumentException::class],
        ];
    }
}
