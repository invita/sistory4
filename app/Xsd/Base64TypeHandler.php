<?php
namespace App\Xsd;

use JMS\Serializer\XmlSerializationVisitor;
use JMS\Serializer\XmlDeserializationVisitor;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\Context;

class Base64TypeHandler implements SubscribingHandlerInterface
{
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'xml',
                'type' => 'App\Xsd\Base64TypeHandler',
                'method' => 'deserializeBase64Type'
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'App\Xsd\Base64TypeHandler',
                'method' => 'serializeBase64Type'
            )
        );
    }

    public function serializeBase64Type(XmlSerializationVisitor $visitor, $data, array $type, Context $context)
    {
        // serialize your object here
    }

    public function deserializeBase64Type(XmlDeserializationVisitor $visitor, $data, array $type, Context $context)
    {
        // deserialize your object here
        /*
        $value = ["text" => (string)$data];
        foreach ($data->getNamespaces() as $ns => $_) {
            foreach ($data->attributes($ns, true) as $attrKey => $attrVal) {
                $value[$ns."_".$attrKey] = (string)$attrVal;
            }
        }
        */
        //print_r($value);

        return (string)$data;
    }

    public function toArray() {
        return $this;
    }
}