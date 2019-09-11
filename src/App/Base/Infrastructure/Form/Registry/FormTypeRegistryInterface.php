<?php

namespace CTIC\App\Base\Infrastructure\Form\Registry;

interface FormTypeRegistryInterface
{
    /**
     * @param string $identifier
     * @param string $typeIdentifier
     * @param string $formType
     */
    public function add(string $identifier, string $typeIdentifier, string $formType): void;

    /**
     * @param string $identifier
     * @param string $typeIdentifier
     *
     * @return string|null
     */
    public function get(string $identifier, string $typeIdentifier): ?string;

    /**
     * @param string $identifier
     * @param string $typeIdentifier
     *
     * @return bool
     */
    public function has(string $identifier, string $typeIdentifier): bool;
}
