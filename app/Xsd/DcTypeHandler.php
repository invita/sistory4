<?php
namespace App\Xsd;

use JMS\Serializer\XmlSerializationVisitor;
use JMS\Serializer\XmlDeserializationVisitor;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\Context;

class DcTypeHandler implements SubscribingHandlerInterface
{
    private $elName;
    private $elValue;

    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'xml',
                'type' => 'App\Xsd\DcTypeHandler',
                'method' => 'deserializeAnyType'
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'xml',
                'type' => 'App\Xsd\DcTypeHandler',
                'method' => 'serializeAnyType'
            )
        );
    }

    public function serializeAnyType(XmlSerializationVisitor $visitor, $data, array $type, Context $context)
    {
    }

    public function deserializeAnyType(XmlDeserializationVisitor $visitor, $data, array $type, Context $context)
    {
        // deserialize your object here


        $elementName = $data->getName();
        $value = (array)$data;

        return DcTypeHandler::create($elementName, $value);
    }

    public static function create($elName, $elValue) {
        $result = new DcTypeHandler();
        $result->elName = $elName;
        $result->elValue = $elValue;
        return $result;
    }

    public function toArray() {
        return $this->elValue;
    }
}