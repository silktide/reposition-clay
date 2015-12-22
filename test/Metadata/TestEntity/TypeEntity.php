<?php

namespace Silktide\Reposition\Clay\Test\Metadata\TestEntity;

use Downsider\Clay\Model\ModelTrait;

class TypeEntity
{

    protected $boolProp;

    protected $intProp;

    protected $floatProp;

    protected $stringProp;

    protected $untypedProp;

    protected $datetimeProp;

    protected $arrayProp;

    protected $noSetterProp;

    protected $noGetterProp;

    /**
     * @param mixed $boolProp
     */
    public function setBoolProp($boolProp)
    {
        $this->boolProp = (bool) $boolProp;
    }

    /**
     * @return mixed
     */
    public function getBoolProp()
    {
        return $this->boolProp;
    }

    /**
     * @param mixed $floatProp
     */
    public function setFloatProp($floatProp)
    {
        $this->floatProp = (float) $floatProp;
    }

    /**
     * @return mixed
     */
    public function getFloatProp()
    {
        return $this->floatProp;
    }

    /**
     * @param mixed $intProp
     */
    public function setIntProp($intProp)
    {
        $this->intProp = (int) $intProp;
    }

    /**
     * @return mixed
     */
    public function getIntProp()
    {
        return $this->intProp;
    }

    /**
     * @param mixed $stringProp
     */
    public function setStringProp($stringProp)
    {
        $this->stringProp = (string) $stringProp;
    }

    /**
     * @return mixed
     */
    public function getStringProp()
    {
        return $this->stringProp;
    }

    /**
     * @param mixed $untypedProp
     */
    public function setUntypedProp($untypedProp)
    {
        $this->untypedProp = $untypedProp;
    }

    /**
     * @return mixed
     */
    public function getUntypedProp()
    {
        return $this->untypedProp;
    }

    /**
     * @param mixed $datetimeProp
     */
    public function setDatetimeProp(\DateTime $datetimeProp)
    {
        $this->datetimeProp = $datetimeProp;
    }

    /**
     * @return mixed
     */
    public function getDatetimeProp()
    {
        return $this->datetimeProp;
    }

    /**
     * @param mixed $arrayProp
     */
    public function setArrayProp(array $arrayProp)
    {
        $this->arrayProp = $arrayProp;
    }

    /**
     * @return mixed
     */
    public function getArrayProp()
    {
        return $this->arrayProp;
    }

    /**
     * @return mixed
     */
    public function getNoSetterProp()
    {
        return $this->noSetterProp;
    }

    /**
     * @param mixed $noGetterProp
     */
    public function setNoGetterProp($noGetterProp)
    {
        $this->noGetterProp = $noGetterProp;
    }


} 