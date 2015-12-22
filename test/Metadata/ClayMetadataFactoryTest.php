<?php

namespace Silktide\Reposition\Clay\Test\Metadata;

use Silktide\Reposition\Clay\Metadata\ClayMetadataFactory;
use Silktide\Reposition\Metadata\EntityMetadata;

class ClayMetadataFactoryTest extends \PHPUnit_Framework_TestCase {

    /**
     * @dataProvider metadataProvider
     *
     * @param $entity
     * @param $expectedMetadata
     */
    public function testCreatingMetadata($entity, $expectedMetadata, $unexpectedMetadata)
    {
        $metadataFactory = new ClayMetadataFactory();
        $metadata = $metadataFactory->createMetadata($entity);

        $metadataFields = $metadata->getFields();

        $type = EntityMetadata::METADATA_FIELD_TYPE;
        $getter = EntityMetadata::METADATA_FIELD_GETTER;
        $setter = EntityMetadata::METADATA_FIELD_SETTER;

        foreach ($expectedMetadata as $field => $meta) {
            $this->assertArrayHasKey($field, $metadataFields);
            $fieldMeta = $metadataFields[$field];
            $this->assertInternalType("array", $fieldMeta);
            $this->assertArrayHasKey($type, $fieldMeta);
            $this->assertEquals($meta["type"], $fieldMeta[$type]);
            $this->assertArrayHasKey($getter, $fieldMeta);
            $this->assertEquals($meta["getter"], $fieldMeta[$getter]);
            $this->assertArrayHasKey($setter, $fieldMeta);
            $this->assertEquals($meta["setter"], $fieldMeta[$setter]);
        }

        foreach ($unexpectedMetadata as $field) {
            $this->assertArrayNotHasKey($field, $metadataFields);
        }

    }

    public function metadataProvider()
    {
        $type = EntityMetadata::METADATA_FIELD_TYPE;

        return [
            [ // types
                "Silktide\\Reposition\\Clay\\Test\\Metadata\\TestEntity\\TypeEntity",
                [
                    "bool_prop" => ["type" => EntityMetadata::FIELD_TYPE_BOOL, "getter" => "getBoolProp", "setter" => "setBoolProp"],
                    "int_prop" => [$type => EntityMetadata::FIELD_TYPE_INT, "getter" => "getIntProp", "setter" => "setIntProp"],
                    "float_prop" => [$type => EntityMetadata::FIELD_TYPE_FLOAT, "getter" => "getFloatProp", "setter" => "setFloatProp"],
                    "string_prop" => [$type => EntityMetadata::FIELD_TYPE_STRING, "getter" => "getStringProp", "setter" => "setStringProp"],
                    "untyped_prop" => [$type => EntityMetadata::FIELD_TYPE_STRING, "getter" => "getUntypedProp", "setter" => "setUntypedProp"],
                    "datetime_prop" => [$type => EntityMetadata::FIELD_TYPE_DATETIME, "getter" => "getDatetimeProp", "setter" => "setDatetimeProp"],
                    "array_prop" => [$type => EntityMetadata::FIELD_TYPE_ARRAY, "getter" => "getArrayProp", "setter" => "setArrayProp"]
                ],
                [
                    "no_getter_prop",
                    "no_setter_prop"
                ]
            ]
        ];
    }

}
 