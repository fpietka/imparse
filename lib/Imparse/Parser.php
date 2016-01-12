<?php

namespace Imparse;

class Parser
{
    private $iptclabels = [
        '1#000' => 'Model Version',
        '1#005' => 'Destination',
        '1#020' => 'File Format',
        '1#022' => 'File Format Version',
        '1#030' => 'Service Identifier',
        '1#040' => 'Envelope Number',
        '1#050' => 'Product I.D.',
        '1#060' => 'Envelope Priority',
        '1#070' => 'Date Sent',
        '1#080' => 'Time Sent',
        '1#090' => 'Coded Character Set',
        '1#100' => 'UNO',
        '1#120' => 'ARM Identifier',
        '1#122' => 'ARM Version',
        '2#000' => 'Record Version',
        '2#003' => 'Object Type Reference',
        '2#004' => 'Object Attribute Reference',
        '2#005' => 'Object Name',
        '2#007' => 'Edit Status',
        '2#008' => 'EditorialUpdate',
        '2#010' => 'Urgency',
        '2#012' => 'Subject Reference',
        '2#015' => 'Category',
        '2#020' => 'Supplemental Category',
        '2#022' => 'Fixture Identifier',
        '2#025' => 'Keywords',
        '2#026' => 'Content Location Code',
        '2#027' => 'Content Location Name',
        '2#030' => 'Release Date',
        '2#035' => 'Release Time',
        '2#037' => 'ExpirationDate',
        '2#038' => 'Expiration Time',
        '2#040' => 'Special Instructions',
        '2#042' => 'Action Advised',
        '2#045' => 'Reference Service',
        '2#047' => 'Reference Date',
        '2#050' => 'Reference Number',
        '2#055' => 'Date Created',
        '2#060' => 'Time Created',
        '2#062' => 'Digital Creation Date',
        '2#063' => 'Digital Creation Time',
        '2#065' => 'Originating Program',
        '2#070' => 'Program Version',
        '2#075' => 'Object Cycle',
        '2#080' => 'By-line',
        '2#085' => 'By-line Title',
        '2#090' => 'City',
        '2#092' => 'Sublocation',
        '2#095' => 'Province/State',
        '2#100' => 'Country/Primary Location Code',
        '2#101' => 'Country/Primary Location Name',
        '2#103' => 'Original Transmission Reference',
        '2#105' => 'Headline',
        '2#110' => 'Credit',
        '2#115' => 'Source',
        '2#116' => 'Copyright Notice',
        '2#118' => 'Contact',
        '2#120' => 'Caption/Abstract',
        '2#122' => 'Writer/Editor',
        '2#125' => 'Rasterized Caption',
        '2#130' => 'Image Type',
        '2#131' => 'Image Orientation',
        '2#135' => 'Language Identifier',
        '2#150' => 'Audio Type',
        '2#151' => 'Audio SamplingRate',
        '2#152' => 'Audio Sampling Resolution',
        '2#153' => 'Audio Duration',
        '2#154' => 'Audio Outcue',
        '2#200' => 'ObjectData Preview File Format',
        '2#201' => 'ObjectData Preview File Format Version',
        '2#202' => 'ObjectData Preview Data'
    ];

    private $metadata = array(
        'exif' => null,
        'iptc' => null,
        'xmp' => null
    );

    private $resource;

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function readExif()
    {
        $this->metadata['exif'] = exif_read_data($this->resource);
    }

    public function readIptc()
    {
        getimagesize($this->resource, $info);

        if (isset($info['APP13'])) {
            $data = iptcparse($info['APP13']);
            $map = $this->iptclabels;

            foreach ($data as $key => $value) {
                $newData[$map[$key]] = $value;
            }

            $this->metadata['iptc'] = $newData;
        }
    }

    public function readXmp()
    {
        $xml = $this->readXmpData();
        if ($xml !== null) {
            $xml = (new \DOMDocument())->loadXML($xml);
        }
        $this->metadata['xmp'] = $xml;
    }

    private function readXmpData($chunk_size = 10000)
    {
        $buffer = null;
        $file_pointer = fopen($this->resource, 'r');

        $chunk = fread($file_pointer, $chunk_size);
        $posStart = strpos($chunk, '<x:xmpmeta');
        if ($posStart !== false) {
            $buffer = substr($chunk, $posStart);
            $posEnd = strpos($buffer, '</x:xmpmeta>');
            $buffer = substr($buffer, 0, $posEnd + 12);
        }

        $complete = feof($file_pointer);
        fclose($file_pointer);

        // recursion here
        if (!$complete
            && !strpos($buffer, '</x:xmpmeta>')) {
            $buffer = $this->readXmpData($chunk_size * 2);
        }

        return $buffer;
    }

    public function getMetaData()
    {
        return $this->metadata;
    }
}
