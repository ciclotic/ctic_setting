<?php
namespace CTIC\App\Permission\Domain;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use CTIC\App\Base\Domain\IdentifiableTrait;
use CTIC\App\Permission\Domain\Validation\PermissionValidation;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="CTIC\App\Permission\Infrastructure\Repository\PermissionRepository")
 */
class Permission implements PermissionInterface
{
    use IdentifiableTrait;
    use PermissionValidation;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    public $route;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $type;

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return bool
     */
    public function setType($type): bool
    {
        if (
            $type != self::TYPE_ADMINISTRATOR &&
            $type != self::TYPE_USER &&
            $type != self::TYPE_ANONYMOUS &&
            $type != self::TYPE_EMPLOYEE
        ) {
            return false;
        }

        $this->type = $type;

        return true;
    }
}