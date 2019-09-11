<?php
namespace CTIC\App\Privacy\Domain;

use Doctrine\ORM\Mapping as ORM;
use CTIC\App\Base\Domain\IdentifiableTrait;
use CTIC\App\Privacy\Domain\Validation\PrivacyValidation;

/**
 * @ORM\Entity(repositoryClass="CTIC\App\Privacy\Infrastructure\Repository\PrivacyRepository")
 */
class Privacy implements PrivacyInterface
{
    use IdentifiableTrait;
    use PrivacyValidation;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var boolean
     */
    public $own;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $type;

    /**
     * @return bool
     */
    public function isOwn(): bool
    {
        return $this->own;
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
            $type != self::TYPE_CREATOR ||
            $type != self::TYPE_CREATOR_AND_ASIGNED_USERS ||
            $type != self::TYPE_PUBLIC
        ) {
            return false;
        }

        $this->type = $type;

        return true;
    }
}