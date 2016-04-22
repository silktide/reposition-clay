<?php

namespace Silktide\Reposition\Clay\Hydrator;

use Silktide\Reposition\Collection\Collection;
use Silktide\Reposition\Exception\HydrationException;
use Silktide\Reposition\Hydrator\EntityFactoryInterface;
use Silktide\Reposition\Normaliser\NormaliserInterface;
use Silktide\Reposition\Hydrator\HydratorInterface;

/**
 *
 */
class ClayHydrator implements HydratorInterface
{

    /**
     * @var NormaliserInterface
     */
    protected $normaliser;

    protected $entityFactory;

    public function __construct(EntityFactoryInterface $entityFactory)
    {
        $this->entityFactory = $entityFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function hydrate(array $data, array $options = ["output" => "normalise"])
    {
        $data = $this->normalise($data, $options);
        return $this->doHydrate($data, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function hydrateAll(array $data, array $options = ["output" => "normalise"])
    {
        $data = $this->normalise($data, $options);
        $collection = [];
        foreach ($data as $i => $subData) {
            $collection[$i] = $this->doHydrate($subData, $options);
        }
        return $collection;
    }

    /**
     * @param array $data
     * @param array $options
     * @return object
     */
    protected function doHydrate(array $data, array $options = [])
    {
        if ($options["output"] != "applyModel" || empty($options["entityClass"])) {
            return $data;
        }
        $this->checkClass($options["entityClass"]);

        $entity = $this->entityFactory->create($options["entityClass"], $data);

        if (!empty($options["trackCollectionChanges"])) {
            // if this entity has collections, track any changes
            foreach (get_class_methods($entity) as $method) {
                if (strpos($method, "get") === 0) {
                    $value = $entity->{$method}();
                    if ($value instanceof Collection) {
                        $value->setChangeTracking();
                    }
                }
            }
        }
        return $entity;
    }

    /**
     * @param NormaliserInterface $normaliser
     */
    public function setNormaliser(NormaliserInterface $normaliser)
    {
        $this->normaliser = $normaliser;
    }

    protected function normalise($data, $options)
    {
        if ($options["output"] != "raw" && $this->normaliser instanceof NormaliserInterface) {
            $data = $this->normaliser->denormalise($data, $options);
        }
        return $data;
    }

    protected function validateOptions(array $options)
    {
        if (empty($options["output"]) || !in_array($options["output"], ["raw", "normalise", "applyModel"])) {
            throw new HydrationException("Invalid options, 'output' must be one of 'raw', 'normalise' or 'applyModel'");
        }
        if ($options["output"] == "applyModel" && empty($options["entityClass"])) {
            throw new HydrationException("Invalid options, cannot apply a model without an entity class");
        }
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