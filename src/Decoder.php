<?php

declare(strict_types = 1);

namespace Json;

use JsonException;
use StdClass;

class Decoder
{
    /** @var string $json */
    private $json;

    /** @var int $options */
    private $options;

    /** @var bool $assoc */
    private $assoc;

    /** @var int $depth */
    private $depth;

    /**
     * @param mixed $json
     * @param int $options
     * @param bool $assoc
     * @param int $depth
     */
    public function __construct($json, int $options = 0, bool $assoc = false, int $depth = 512)
    {
        $this->json = $json;
        $this->assoc = $assoc;
        $this->depth = $depth;
        $this->options = $options;
    }

    /**
     * @return mixed[]
     *
     * @throws JsonException
     */
    public function decodeToArray(): array
    {
        return $this->decode(true);
    }

    /**
     * @return StdClass
     *
     * @throws JsonException
     */
    public function decodeToObject(): StdClass
    {
        return $this->decode(false);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        try {
            $this->decode();
            $isValid = true;
        } catch (JsonException $e) {
            $isValid = false;
        }

        return $isValid;
    }

    /**
     * @param bool|null $assoc
     *
     * @return mixed
     *
     * @throws JsonException
     */
    public function decode(bool $assoc = null)
    {
        return json_decode(
            $this->json,
            $assoc ?? $this->assoc,
            $this->depth,
            JSON_THROW_ON_ERROR | $this->options
        );
    }
}
