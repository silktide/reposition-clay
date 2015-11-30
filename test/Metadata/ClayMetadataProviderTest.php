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
                        "bool_prop" => [$type => EntityMetadata::FIELD_TYPE_BOOL],
                        "int_prop" => [$type => EntityMetadata::FIELD_TYPE_INT],
                        "float_prop" => [$type => EntityMetadata::FIELD_TYPE_FLOAT],
                        "string_prop" => [$type => EntityMetadata::FIELD_TYPE_STRING],
                        "untyped_prop" => [$type => EntityMetadata::FIELD_TYPE_STRING],
                        "datetime_prop" => [$type => EntityMetadata::FIELD_TYPE_DATETIME],
                        "array_prop" => [$type => EntityMetadata::FIELD_TYPE_ARRAY]
                    ]
                ]
            ]
        ];
    }

}
 