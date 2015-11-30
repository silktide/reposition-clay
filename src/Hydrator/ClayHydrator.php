<?php

namespace Silktide\Reposition\Clay\Hydrator;

use Silktide\Reposition\Exception\HydrationException;
use Silktide\Reposition\Normaliser\NormaliserInterface;
use Silktide\Reposition\Hydrator\HydratorInterface;
use Silktide\Reposition\Collection\CollectionFactory;

/**
 *
 */
class ClayHydrator implements HydratorInterface
{

    /**
     * @var NormaliserInterface
     */
    protected $normaliser;

    protected $collectionFactory;

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->$collectionFactory = $collectionFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, $entityClass, array $options = [])
    {
        $this->checkClass($entityClass);
        return $this->doHydrate($data, $entityClass, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function hydrateAll(array $data, $entityClass, array $options = [])
    {
        $this->checkClass($entityClass);
        $collection = [];
        foreach ($data as $i => $subData) {
            $collection[$i] = $this->doHydrate($subData, $entityClass, $options);
        }
        return $collection;
    }

    /**
     * @param array $data
     * @param string $class
     * @param array $options
     * @return object
     */
    protected function doHydrate(array $data, $class, array $options = [])
    {
        if ($this->normaliser instanceof NormaliserInterface) {
            $data = $this->normaliser->denormalise($data, $options);
        }
        return new $class($data, $this->collectionFactory);
    }

    /**
     * @param NormaliserInterface $normaliser
     */
    public function setNormaliser(NormaliserInterface $normaliser)
    {
        $this->normaliser = $normaliser;
    }

    /**
     * @param string $class
     * @throws HydrationException
     */
    protected function checkClass($class)
    {
        if (!is_string($class)) {
            throw new HydrationException("Invalid class name (not a string)");
        }
        if (!class_exists($class)) {
            throw new HydrationException("Could not hydrate. The class '$class' does not exist");
        }
        if (!method_exists($class, "loadData") || !method_exists($class, "toArray")) {
            throw new HydrationException("Could not hydrate. The class '$class' does not use the Clay ModelTrait");
        }
    }

} 