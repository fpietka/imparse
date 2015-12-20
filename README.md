[![Build Status](https://travis-ci.org/fpietka/imparse.svg)](https://travis-ci.org/fpietka/imparse) [![Code Coverage](https://scrutinizer-ci.com/g/fpietka/imparse/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/fpietka/imparse/?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fpietka/imparse/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fpietka/imparse/?branch=master)

Imparse - Image Metadata Parser
-------------------------------

A library to parse Exif, IPTC and XMP metadata from an image.

# Usage

Load an image in the parser:

```php
$parser = new Imparse\Parser('picture.jpg');
```

Then depending on the type of metadata you want to fetch, call the appropriate method.

```php
$parser->readExif();
$parser->getMetaData()['exif'];
```

```php
$parser->readIptc();
$parser->getMetaData()['iptc'];
```

```php
$parser->readXmp();
$parser->getMetaData()['xmp'];

```

# Resources

[IPTC Specifications](http://www.iptc.org/std/IIM/4.2/specification/IIMV4.2.pdf)
