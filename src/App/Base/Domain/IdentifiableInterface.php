<?php
namespace CTIC\App\Base\Domain;


interface IdentifiableInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id): void;
}