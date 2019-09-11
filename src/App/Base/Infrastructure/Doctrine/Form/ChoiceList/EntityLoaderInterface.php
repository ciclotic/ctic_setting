<?php

namespace CTIC\App\Base\Infrastructure\Doctrine\Form\ChoiceList;

/**
 * Custom loader for entities in the choice list.
 */
interface EntityLoaderInterface
{
    /**
     * Returns an array of entities that are valid choices in the corresponding choice list.
     *
     * @return array The entities
     */
    public function getEntities();

    /**
     * Returns an array of entities matching the given identifiers.
     *
     * @param string $identifier The identifier field of the object. This method
     *                           is not applicable for fields with multiple
     *                           identifiers.
     * @param array  $values     The values of the identifiers
     *
     * @return array The entities
     */
    public function getEntitiesByIds($identifier, array $values);
}
