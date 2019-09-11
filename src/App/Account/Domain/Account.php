<?php
namespace CTIC\App\Account\Domain;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use CTIC\App\Base\Domain\IdentifiableTrait;
use CTIC\App\Account\Domain\Validation\AccountValidation;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="CTIC\App\Account\Infrastructure\Repository\AccountRepository")
 */
class Account implements AccountInterface
{
    use IdentifiableTrait;
    use AccountValidation;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    public $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}