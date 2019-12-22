# php-blurhash [![Build Status](https://travis-ci.org/kornrunner/php-blurhash.svg?branch=master)](https://travis-ci.org/kornrunner/php-blurhash)  [![Coverage Status](https://coveralls.io/repos/github/kornrunner/php-blurhash/badge.svg?branch=master)](https://coveralls.io/github/kornrunner/php-blurhash?branch=master)

A pure PHP implementation of [Blurhash](https://github.com/woltapp/blurhash). The API is stable, however the hashing function in either direction may not be.

Blurhash is an algorithm written by [Dag Ågren](https://github.com/DagAgren) for [Wolt (woltapp/blurhash)](https://github.com/woltapp/blurhash) that encodes an image into a short (~20-30 byte) ASCII string. When you decode the string back into an image, you get a gradient of colors that represent the original image. This can be useful for scenarios where you want an image placeholder before loading, or even to censor the contents of an image [a la Mastodon](https://blog.joinmastodon.org/2019/05/improving-support-for-adult-content-on-mastodon/).

## Installation


```sh
$ composer require kornrunner/blurhash
```

## Usage

Encoding an image to blurhash expects two-dimensional array of colors of image pixels, sample code:

```php
<?php

require_once 'vendor/autoload.php';

use kornrunner\Blurhash\Blurhash;

$file  = 'test/data/img1.jpg';
$image = imagecreatefromjpeg ($file);
list($width, $height) = getimagesize($file);

$pixels = [];
for ($y = 0; $y < $height; ++$y) {
    $row = [];
    for ($x = 0; $x < $width; ++$x) {
        $rgb = imagecolorat($image, $x, $y);

        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;

        $row[] = [$r, $g, $b];
    }
    $pixels[] = $row;
}

$components_x = 4;
$components_y = 3;
$blurhash = Blurhash::encode($pixels, $components_x, $components_y);
// LEHV9uae2yk8pyo0adR*.7kCMdnj
```

For decoding the blurhash people will likely go for some other implementation (JavaScript/TypeScript).
PHP decoder returns a pixel array that can be used to generate the image:

```php
<?php

require_once 'vendor/autoload.php';

use kornrunner\Blurhash\Blurhash;

$blurhash = 'LEHV6nWB2yk8pyo0adR*.7kCMdnj';
$width    = 269;
$height   = 173;

$pixels = Blurhash::decode($blurhash, $width, $height);
$image  = imagecreatetruecolor($width, $height);
for ($y = 0; $y < $height; ++$y) {
    for ($x = 0; $x < $width; ++$x) {
        $pixel = $pixels[$y][$x];
        imagesetpixel($image, $x, $y, imagecolorallocate($image, $pixel[0], $pixel[1], $pixel[2]));
    }
}
imagepng($image, 'blurhash.png');
```

## Contributing

Issues, feature requests or improvements welcome!

## Licence

This project is licensed under the [MIT License](LICENSE).
