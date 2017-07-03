<?php
namespace App\Xsd;

use JMS\Serializer\XmlSerializationVisitor;
use JMS\Serializer\XmlDeserializationVisitor;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\Context;

class AnySimpleTypeHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'xml',
                'type' => 'App\Xsd\AnySimpleTypeHandler',
                'method' => 'deserializeAnySimpleType'
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'App\Xsd\AnySimpleTypeHandler',
                'method' => 'serializeAnySimpleType'
            )
        );
    }

    public function serializeAnySimpleType(XmlSerializationVisitor $visitor, $data, array $type, Context $context)
    {
        // serialize your object here
        //$i=1;
        //echo "serializeAnyType S"; print_r($data);
    }

    public function deserializeAnySimpleType(XmlDeserializationVisitor $visitor, $data, array $type)
    {
        // deserialize your object here
        //$i=1;
        //echo "deserializeAnyType S"; print_r($data);
    }
}