<?php

namespace CTIC\App\Base\Infrastructure\Serializer;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;

/**
 */
interface SerializerInterface
{
    /**
     * @param mixed   $data
     * @param string  $format
     * @param SerializationContext $context
     *
     * @return string
     */
    public function serialize($data, $format, SerializationContext $context);

    /**
     * @param string  $data
     * @param string  $type
     * @param string  $format
     * @param DeserializationContext $context
     *
     * @return mixed
     */
    public function deserialize($data, $type, $format, DeserializationContext $context);
}
