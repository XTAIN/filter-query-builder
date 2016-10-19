<?php

namespace XTAIN\FilterQueryBuilder\Rule;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\XmlDeserializationVisitor;
use JMS\Serializer\XmlSerializationVisitor;

class Value
{
    const KEY = 'value';

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Value constructor.
     *
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function serializeToXml(XmlSerializationVisitor $visitor, $value, SerializationContext $context)
    {
        /** @var \DOMDocument $document */
        $document = $visitor->getDocument();

        if ($document === null) {
            $visitor->setDefaultRootName(self::KEY);
            $visitor->document = $visitor->createDocument();
            $visitor->document->documentElement->setAttribute('format', 'json');
            $visitor->document->documentElement->nodeValue = json_encode($this->value);
        } else {
            $element = $document->createElement(self::KEY, json_encode($this->value));
            $element->setAttribute('format', 'json');

            return $element;
        }
    }

    public function serializeToJson(JsonSerializationVisitor $visitor, $value, SerializationContext $context)
    {
        if ($visitor->getRoot() === null) {
            $visitor->setRoot($this->value);
        } else {
            return array(
                self::KEY => $this->value
            );
        }
    }

    public function deserializeFromXml(
        XmlDeserializationVisitor $visitor,
        $value,
        DeserializationContext  $context
    ) {
        if (!($value instanceof \SimpleXMLElement)) {
            throw new \RuntimeException(sprintf(
                'expected object of type %s, type %s give',
                '\SimpleXMLElement',
                get_class($value)
            ));
        }

        $value = (string) $value;
        $this->value = json_decode($value);
    }

    public function deserializeFromJson(JsonDeserializationVisitor $visitor, $value, DeserializationContext $context)
    {
        $this->value = $value;
    }
}