<?php

declare(strict_types = 1);

namespace Json;

use JsonException;
use StdClass;
use PHPUnit\Framework\TestCase;

class EncoderTest extends TestCase
{
    /**
     * @param mixed $data
     * @param string $expected
     * @param int $options
     *
     * @throws JsonException
     *
     * @dataProvider correctDataProvider
     */
    public function testCorrectEncoding($data, string $expected, int $options = 0): void
    {
        $encoder = new Encoder($data, $options);
        $this->assertJsonStringEqualsJsonString($expected, $encoder->encode());
    }

    /**
     * @return mixed[]
     */
    public function correctDataProvider(): array
    {
        $std = new StdClass();
        $std->self = $std;
        $std->amp = 'foo&bar';

        return [
            ['data' => null, 'expected' => 'null'],
            ['data' => '', 'expected' => '""'],
            ['data' => 123, 'expected' => '123'],
            ['data' => 'test', 'expected' => '"test"'],
            ['data' => '/test/', 'expected' => '"\/test\/"'],
            ['data' => [], 'expected' => '[]'],
            ['data' => [1, 2, 3], 'expected' => '[1,2,3]'],
            ['data' => new StdClass(), 'expected' => '{}'],
            ['data' => ['test' => 'test'], 'expected' => '{"test":"test"}'],
            ['data' => 'foo&bar', 'expected' => '"foo\u0026bar"', 'options' => JSON_HEX_AMP],
            ['data' => NAN, 'expected' => '0', 'options' => JSON_PARTIAL_OUTPUT_ON_ERROR],
            [
                'data' => $std,
                'expected' => '{"self":null,"amp":"foo\u0026bar"}',
                'options' => JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_HEX_AMP
            ],
            [
                'data' => ['€', 'http://test.com/', '05'],
                'expected' => '["€","http://test.com/",5]',
                'options' => JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
            ],
        ];
    }

    /**
     * @param mixed[] $data
     *
     * @throws JsonException
     *
     * @dataProvider incorrectDataProvider
     */
    public function testIncorrectEncoding($data): void
    {
        $this->expectException(JsonException::class);
        (new Encoder($data))->encode();
    }

    /**
     * @return mixed[]
     */
    public function incorrectDataProvider(): array
    {
        $std = new StdClass();
        $std->self = $std;

        return [
            ['data' => NAN],
            ['data' => INF],
            ['data' => $std],
        ];
    }
}
