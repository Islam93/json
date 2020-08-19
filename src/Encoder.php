<?php

declare(strict_types = 1);

namespace Json;

use JsonException;

class Encoder
{
    /** @var mixed $data */
    private $data;

    /** @var int $options */
    private $options;

    /** @var int $depth */
    private $depth;

    /**
     * @param mixed $data
     * @param int $options
     * @param int $depth
     */
    public function __construct($data, int $options = 0, int $depth = 512)
    {
        $this->data = $data;
        $this->options = $options;
        $this->depth = $depth;
    }

    /**
     * @return string
     *
     * @throws JsonException
     */
    public function encode(): string
    {
        return (string) json_encode($this->data, JSON_THROW_ON_ERROR | $this->options, $this->depth);
    }
}
