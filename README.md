JSON
---

[![Minimum PHP Version](https://img.shields.io/badge/php-%5E7.3-8892BF)](https://php.net/)

Wrapper for SPL json functions

Usage
---
###### decoding
```php
<?php

use Json\Decoder;

$decoder = new Decoder('{"test":"test"}');
$decoder->decode(); // StdClass instance with 'test' property
$decoder->decodeToArray(); // ['test' => 'test']
$decoder->decodeToObject(); // StdClass instance with 'test' property

$decoder = new Decoder('{"test":"test"}', 0, true);
$decoder->decode(); // ['test' => 'test']
$decoder->decodeToArray(); // ['test' => 'test']
$decoder->decodeToObject(); // StdClass instance with 'test' property
```
###### encoding
```php
<?php

use Json\Encoder;

$encoder = new Encoder(['test' => 'test']);
$encoder->encode(); // '{"test":"test"}'

$encoder = new Encoder(
    "['€', 'http://test.com/', '05']",
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK
);
$encoder->encode(); // '["€","http://test.com/",5]'
```
