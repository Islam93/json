<?php

declare(strict_types = 1);

namespace Json;

use JsonException;
use StdClass;
use PHPUnit\Framework\TestCase;

class DecoderTest extends TestCase
{
    /**
     * @param string $json
     * @param mixed $expected
     * @param int $options
     *
     * @throws JsonException
     *
     * @dataProvider correctToArrayDataProvider
     */
    public function testCorrectToArrayDecoding(string $json, $expected, int $options = 0): void
    {
        $decoder = new Decoder($json, $options);
        $this->assertEquals($expected, $decoder->decodeToArray());
    }

    /**
     * @return mixed[]
     */
    public function correctToArrayDataProvider(): array
    {
        return [
            ['json' => '{}', 'expected' => []],
            ['json' => '{"test":"test"}', 'expected' => ['test' => 'test']],
        ];
    }

    /**
     * @param string $json
     * @param mixed $expected
     * @param int $options
     *
     * @throws JsonException
     *
     * @dataProvider correctToObjectDataProvider
     */
    public function testCorrectToObjectDecoding(string $json, $expected, int $options = 0): void
    {
        $decoder = new Decoder($json, $options);
        $this->assertEquals($expected, $decoder->decodeToObject());
    }

    /**
     * @return mixed[]
     */
    public function correctToObjectDataProvider(): array
    {
        $std = new StdClass();
        $std->test = 'test';

        return [
            ['json' => '{}', 'expected' => new StdClass()],
            ['json' => '{"test":"test"}', 'expected' => $std],
        ];
    }

    /**
     * @param string $json
     * @param mixed $expected
     * @param int $options
     * @param bool $assoc
     *
     * @dataProvider correctDataProvider
     */
    public function testIsValid(string $json, $expected, int $options = 0, bool $assoc = false): void
    {
        $decoder = new Decoder($json, $options, $assoc);
        $this->assertTrue($decoder->isValid());
    }

    /**
     * @param string $json
     * @param mixed $expected
     * @param int $options
     * @param bool $assoc
     *
     * @throws JsonException
     *
     * @dataProvider correctDataProvider
     */
    public function testCorrectDecoding(string $json, $expected, int $options = 0, bool $assoc = false): void
    {
        $decoder = new Decoder($json, $options, $assoc);
        $this->assertEquals($expected, $decoder->decode());
    }

    /**
     * @return mixed[]
     */
    public function correctDataProvider(): array
    {
        $std = new StdClass();
        $std->self = null;
        $std->amp = 'foo&bar';

        return [
            ['json' => 'null', 'expected' => null],
            ['json' => '""', 'expected' => ''],
            ['json' => '123', 'expected' => 123],
            ['json' => '"test"', 'expected' => 'test'],
            ['json' => '{}', 'expected' => [], 'options' => 0, 'assoc' => true],
            ['json' => '[1,2,3]', 'expected' => [1, 2, 3]],
            ['json' => '{}', 'expected' => new StdClass()],
            ['json' => '{"test":"test"}', 'expected' => ['test' => 'test'], 'options' => 0, 'assoc' => true],
            ['json' => '"foo\u0026bar"', 'expected' => 'foo&bar'],
            ['json' => '{"self":null,"amp":"foo\u0026bar"}', 'expected' => $std],
        ];
    }

    /**
     * @param mixed $json
     *
     * @dataProvider incorrectDataProvider
     */
    public function testIsNotValid($json): void
    {
        $decoder = new Decoder($json);
        $this->assertFalse($decoder->isValid());
    }

    /**
     * @param mixed $json
     *
     * @throws JsonException
     *
     * @dataProvider incorrectDataProvider
     */
    public function testIncorrectEncoding($json): void
    {
        $this->expectException(JsonException::class);
        (new Decoder($json))->decode();
    }

    /**
     * @return mixed[]
     */
    public function incorrectDataProvider(): array
    {
        return [
            ['json' => 'invalid json'],
        ];
    }

    /**
     * @param string $json
     * @param mixed $expected
     * @param bool|null $assoc
     *
     * @throws JsonException
     *
     * @dataProvider decodeParamTestProvider
     */
    public function testDecodeParam(string $json, $expected, ?bool $assoc): void
    {
        $decoder = new Decoder($json);
        $decodedData = $assoc === null ? $decoder->decode() : $decoder->decode($assoc);
        $this->assertEquals($expected, $decodedData);
    }

    /**
     * @return mixed[]
     */
    public function decodeParamTestProvider(): array
    {
        return [
            ['json' => '{}', 'expected' => new StdClass(), 'assoc' => null],
            ['json' => '{}', 'expected' => [], 'assoc' => true],
            ['json' => '{}', 'expected' => new StdClass(), 'assoc' => false],
        ];
    }
}
