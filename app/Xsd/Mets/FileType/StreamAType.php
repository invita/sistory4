<?php

namespace App\Xsd\Mets\FileType;

/**
 * Class representing StreamAType
 */
class StreamAType
{

    /**
     * @property \App\Xsd\AnyTypeHandler $__value
     */
    private $__value = null;

    /**
     * Construct
     *
     * @param \App\Xsd\AnyTypeHandler $value
     */
    public function __construct(\App\Xsd\AnyTypeHandler $value)
    {
        $this->value($value);
    }

    /**
     * Gets or sets the inner value
     *
     * @param \App\Xsd\AnyTypeHandler $value
     * @return \App\Xsd\AnyTypeHandler
     */
    public function value()
    {
        if ($args = func_get_args()) {
            $this->__value = $args[0];
        }
        return $this->__value;
    }

    /**
     * Gets a string value
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->__value);
    }

    public function toArray()
    {
        $handleItem = function($item){
            if(is_object($item)){
                if(method_exists($item, "toArray")){
                    return $item->toArray();
                } elseif($item instanceOf \DateTime){
                    return $item->format('Y-m-d\TH:i:s\Z');
                }
                return (string)$item;
            }

            return $item;
        };

        $array = [];
        $methods = get_class_methods($this);
        foreach ($methods as $method){
            if(substr($method, 0, 3) == "get"){
                $var = $this->{$method}();
                $key = substr($method, 3);
                if(is_array($var)){
                    $array[$key] = [];
                    foreach ($var as $k => $itm){
                        $array[$key][$k] = $handleItem($itm);
                    }
                } else {
                    $array[$key] = $handleItem($var);
                }
            }
        }

        return $array;
    }


}

