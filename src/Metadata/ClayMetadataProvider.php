<?php

namespace Silktide\Reposition\Clay\Metadata;

use Silktide\Reposition\Exception\MetadataException;
use Silktide\Reposition\Metadata\EntityMetadata;
use Silktide\Reposition\Metadata\MetadataProviderInterface;

class ClayMetadataProvider implements MetadataProviderInterface
{

    protected $getters = [];

    protected $setters = [];

    protected $adders = [];

    /**
     * {@inheritDoc}
     */
    public function getMetadata($reference)
    {
        $ref = new \ReflectionClass($reference);

        $this->findClayMethods($ref);

        $entityMetadata = new EntityMetadata($ref->getName());

        /*
        For each setter (or adder) that has a getter (e.g. a property that is fully accessible) check if it has a
        relationship to another entity class, otherwise detect the property type
        */

        foreach ($this->setters as $property => $setterMethod) {
            /** @var \ReflectionMethod $setterMethod */
            /** @var \ReflectionParameter $firstParam */
            $firstParam = $setterMethod->getParameters()[0];
            if ($firstParam->isArray()) {
                // this is a collection, check for an adder so we can check type on the collection elements
                if (!empty($adders[$property])) {
                    $setterMethod = $this->adders[$property];
                    $firstParam = $setterMethod->getParameters()[0];
                }
            }
            $class = $firstParam->getClass();
            if (!empty($class) && !empty($class->getNamespaceName())) {
                // TODO: relationships
            } else {
                /** @var \ReflectionMethod $getterMethod */
                $getterMethod = $this->getters[$property];
                $getter = $getterMethod->getName();
                $setter = $setterMethod->getName();

                // detect type
                $type = $this->detectPropertyType($ref, $getter, $setter, $property);
                $fieldMetadata = [
                    EntityMetadata::METADATA_FIELD_TYPE => $type
                ];
                $entityMetadata->addFieldMetadata($property, $fieldMetadata);
            }
        }

        return $entityMetadata;

    }

    /**
     * search through all the public methods of this class and save all the getters, setters and adders
     *
     * @param \ReflectionClass $ref
     */
    protected function findClayMethods(\ReflectionClass $ref)
    {
        $publicMethods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($publicMethods as $method) {
            $name = $method->getName();
            $property = lcfirst(substr($name, 3));
            $matches = [];
            if (preg_match("/^(get|set|add)[A-Z]+.*/", $name, $matches)) {
                switch ($name[0]) {
                    case "g":
                        $collection = "getters";
                        break;
                    case "s":
                        $collection = "setters";
                        break;
                    case "a":
                        $collection = "adders";
                        break;
                }
                $this->{$collection}[$property] = $method;
            }
        }
    }

    protected function detectPropertyType(\ReflectionClass $ref, $getter, $setter, $property)
    {
        // if the entity has a constructor, check it doesn't have any required arguments
        $constructor = $ref->getConstructor();
        if (!empty($constructor) && $constructor->getNumberOfRequiredParameters() > 0) {
            throw new MetadataException("Unable to detect property type for '{$ref->getName()}::$property'. Cannot create an instance of the class, as it has a constructor with required arguments");
        }
        $instance = $ref->newInstance();

        // setup detection data
        $data = [
            EntityMetadata::FIELD_TYPE_BOOL => true,
            EntityMetadata::FIELD_TYPE_INT => 46,
            EntityMetadata::FIELD_TYPE_FLOAT => 23.653,
            EntityMetadata::FIELD_TYPE_STRING => "test",
            EntityMetadata::FIELD_TYPE_DATETIME => new \DateTime(),
            EntityMetadata::FIELD_TYPE_ARRAY => [1, 2, 3]
        ];

        // detect types. It is possible we will have more than one test come back positive.
        $results = [];
        foreach ($data as $type => $value) {
            // set the value on this property and get it back again
            // catch exceptions. Means the input was invalid for this property
            try {
                $instance->{$setter}($value);
            } catch (\Exception $e) {
                continue;
            }
            $result = $instance->{$getter}();

            // check if the original value is exactly the same what we got back
            if ($value === $result) {
                $results[$type] = true;
            }
        }

        $count = count($results);

        // if we detected one type, return that
        if ($count == 1) {
            return key($results);
        }
        // if we detected both integer and float, it's a float
        if ($count == 2 && !empty($results[EntityMetadata::FIELD_TYPE_INT]) && !empty($results[EntityMetadata::FIELD_TYPE_FLOAT])) {
            return EntityMetadata::FIELD_TYPE_FLOAT;
        }
        // if we can't tell, default to the string type
        return EntityMetadata::FIELD_TYPE_STRING;
    }

} 