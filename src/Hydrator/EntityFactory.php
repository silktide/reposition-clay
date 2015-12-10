<?php

namespace Silktide\Reposition\Clay\Hydrator;

use Silktide\Reposition\Collection\CollectionFactory;
use Silktide\Reposition\Hydrator\EntityFactoryInterface;

/**
 * EntityFactory
 */
class EntityFactory implements EntityFactoryInterface
{

    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory = null)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function create($class, array $data = [])
    {
        // detect if class uses ModelTrait
        if ($this->usesModelTrait($class)) {
            return new $class($data, $this->collectionFactory);
        }
        return new $class();
    }


    protected function usesModelTrait($subject)
    {
        return method_exists($subject, "toArray");
    }

}