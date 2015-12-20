<?php
namespace Unit\Tests;

use Imparse\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testReadExif()
    {
        $parser = new Parser('tests/resources/drp2091169-sfw-q60-all-wicc.jpg');
        $parser->readExif();

        $this->assertNotNull($parser->getMetaData()['exif']);

        $this->assertNull($parser->getMetaData()['xmp']);
        $this->assertNull($parser->getMetaData()['iptc']);
    }

    public function testReadIptc()
    {
        $parser = new Parser('tests/resources/drp2091169-sfw-q60-all-wicc.jpg');
        $parser->readIptc();

        $this->assertNotNull($parser->getMetaData()['iptc']);

        $this->assertNull($parser->getMetaData()['xmp']);
        $this->assertNull($parser->getMetaData()['exif']);
    }

    public function testReadXmp()
    {
        $parser = new Parser('tests/resources/drp2091169-sfw-q60-all-wicc.jpg');
        $parser->readXmp();

        $this->assertNotNull($parser->getMetaData()['xmp']);

        $this->assertNull($parser->getMetaData()['iptc']);
        $this->assertNull($parser->getMetaData()['exif']);
    }
}
