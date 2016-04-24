<?php

namespace Silktide\Reposition\Clay\Hydrator;

use Downsider\Clay\Model\ClassDiscriminatorTrait;
use Silktide\Reposition\Collection\CollectionFactory;
use Silktide\Reposition\Hydrator\EntityFactoryInterface;

/**
 * EntityFactory
 */
class EntityFactory implements EntityFactoryInterface
{
    use ClassDiscriminatorTrait;

    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory = null)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function create($class, array $data = [])
    {
        // detect if class uses ModelTrait
        if ($this->usesModelTrait($class)) {
            // check if this entity is discriminated
            $ref = $this->discriminateClass(new \ReflectionClass($class), $data);
            return $ref->newInstance($data, $this->collectionFactory);
        }
        return new $class();
    }


    protected function usesModelTrait($subject)
    {
        return method_exists($subject, "toArray");
    }

}