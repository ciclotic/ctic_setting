<?php

namespace CTIC\App\Base\Infrastructure\View\Rest;

/**
 * Specialized ViewInterface that allows dynamic configuration of JMS serializer context aspects.
 *
 */
interface ConfigurableViewHandlerInterface extends ViewHandlerInterface
{
    /**
     * Set the default serialization groups.
     *
     * @param array $groups
     */
    public function setExclusionStrategyGroups($groups);

    /**
     * Set the default serialization version.
     *
     * @param string $version
     */
    public function setExclusionStrategyVersion($version);

    /**
     * If nulls should be serialized.
     *
     * @param bool $isEnabled
     */
    public function setSerializeNullStrategy($isEnabled);
}
