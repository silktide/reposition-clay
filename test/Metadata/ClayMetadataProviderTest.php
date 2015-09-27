<?php

namespace Silktide\Reposition\Clay\Test\Metadata;

use Silktide\Reposition\Clay\Metadata\ClayMetadataProvider;
use Silktide\Reposition\Metadata\EntityMetadata;

class ClayMetadataProviderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @dataProvider metadataProvider
     *
     * @param $entity
     * @param $expectedMetadata
     */
    public function testProvidingMetadata($entity, $expectedMetadata)
    {
        $metadataProvider = new ClayMetadataProvider();
        $metadata = $metadataProvider->getMetadata($entity);

        $metadataArray = [
            "fields" => $metadata->getFields(),
            "relationships" => $metadata->getRelationships()
        ];

        $this->assertEquals($expectedMetadata, $metadataArray);
    }

    public function metadataProvider()
    {
        $type = EntityMetadata::METADATA_FIELD_TYPE;

        return [
            [
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
                    ],
                    "relationships" => []
                ]
            ]
        ];
    }

}
 