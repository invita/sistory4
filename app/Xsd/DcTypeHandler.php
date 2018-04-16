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

        /*
        $elementName = $data->getName();
        $value = (array)$data;
        return DcTypeHandler::create($elementName, $value);
        */


        // $data is SimpleXmlElement that can be iterated for elements with same name
        // Each element can have attributes in different namespaces...
        // Plan: Map every element into array, with all the attributes.

        $elementName = $data->getName();

        $value = [];
        foreach($data as $el) {
            $elValue = ["text" => (string)$el];

            foreach ($el->getNamespaces() as $ns => $_) {
                foreach ($el->attributes($ns, true) as $attrKey => $attrVal) {
                    $elValue[$ns."_".$attrKey] = (string)$attrVal;
                }
            }
            $value[] = $elValue;
        }

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