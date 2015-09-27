<?php

namespace Silktide\Reposition\Clay\Test\Metadata;

use Silktide\Reposition\Clay\Metadata\ClayMetadataFactory;
use Silktide\Reposition\Metadata\EntityMetadata;

class ClayMetadataProviderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @dataProvider metadataProvider
     *
     * @param $entity
     * @param $expectedMetadata
     */
    public function testCreatingMetadata($entity, $expectedMetadata)
    {
        $metadataFactory = new ClayMetadataFactory();
        $metadata = $metadataFactory->createMetadata($entity);

        $metadataArray = [
            "fields" => $metadata->getFields()
        ];

        $this->assertEquals($expectedMetadata, $metadataArray);
    }

    public function metadataProvider()
    {
        $type = EntityMetadata::METADATA_FIELD_TYPE;

        return [
            [ // types
                "Silktide\\Reposition\\Clay\\Test\\Metadata\\TestEntity\\TypeEntity",
                [
                    "fields" => [
                        "boolProp" => [$type => EntityMetadata::FIELD_TYPE_BOOL],
                        "intProp" => [$type => EntityMetadata::FIELD_TYPE_INT],
                        "floatProp" => [$type => EntityMetadata::FIELD_TYPE_FLOAT],
                        "stringProp" => [$type => EntityMetadata::FIELD_TYPE_STRING],
                        "untypedProp" => [$type => EntityMetadata::FIELD_TYPE_STRING],
                        "datetimeProp" => [$type => EntityMetadata::FIELD_TYPE_DATETIME],
                        "arrayProp" => [$type => EntityMetadata::FIELD_TYPE_ARRAY]
                    ]
                ]
            ]
        ];
    }

}
 