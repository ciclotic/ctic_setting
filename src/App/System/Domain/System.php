<?php
namespace CTIC\App\System\Domain;

use Doctrine\ORM\Mapping as ORM;
use CTIC\App\Base\Domain\IdentifiableTrait;
use CTIC\App\System\Domain\Validation\SystemValidation;

/**
 * @ORM\Entity(repositoryClass="CTIC\App\System\Infrastructure\Repository\SystemRepository")
 */
class System implements SystemInterface
{
    use IdentifiableTrait;
    use SystemValidation;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @var string
     */
    public $name;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $styleTPV;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    public $enabledTPVSound;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    public $enabledRoundDiscount;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    public $numberTPVFamilyLines;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    public $numberTPVProductLines;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    public $enabledTPVAutomaticComplements;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $typeTPVPrint;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $typeClientName;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    public $enabled;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getStyleTPV(): int
    {
        return $this->styleTPV;
    }

    /**
     * @param int $styleTPV
     *
     * @return bool
     */
    public function setStyleTPV($styleTPV): bool
    {
        if ($styleTPV != self::STYLE_TPV_DEFAULT) {
            return false;
        }

        $this->styleTPV = $styleTPV;

        return true;
    }

    /**
     * @return bool
     */
    public function isEnabledTPVSound(): bool
    {
        return $this->enabledTPVSound;
    }

    /**
     * @return bool
     */
    public function isEnabledRoundDiscount(): bool
    {
        return $this->enabledRoundDiscount;
    }

    /**
     * @return int
     */
    public function getNumberTPVFamilyLines(): int
    {
        return $this->numberTPVFamilyLines;
    }

    /**
     * @return int
     */
    public function getNumberTPVProductLines(): int
    {
        return $this->numberTPVProductLines;
    }

    /**
     * @return bool
     */
    public function isEnabledTPVAutomaticComplements(): bool
    {
        return $this->enabledTPVAutomaticComplements;
    }

    /**
     * @return int
     */
    public function getTypeTPVPrint(): int
    {
        return $this->typeTPVPrint;
    }

    /**
     * @param int $typeTPVPrint
     *
     * @return bool
     */
    public function setTypeTPVPrint($typeTPVPrint): bool
    {
        if (
            $typeTPVPrint != self::TYPE_TPV_PRINT_NPRINT_NCASH &&
            $typeTPVPrint != self::TYPE_TPV_PRINT_PRINT_CASH &&
            $typeTPVPrint != self::TYPE_TPV_PRINT_NPRINT_CASH &&
            $typeTPVPrint != self::TYPE_TPV_PRINT_PRINT_NCASH
        ) {
            return false;
        }

        $this->typeTPVPrint = $typeTPVPrint;

        return true;
    }

    /**
     * @return int
     */
    public function getTypeClientName(): int
    {
        return $this->typeClientName;
    }

    /**
     * @param int $typeClientName
     *
     * @return bool
     */
    public function setTypeClientName($typeClientName): bool
    {
        if (
            $typeClientName != self::TYPE_TPV_CLIENT_NAME_TAX &&
            $typeClientName != self::TYPE_TPV_CLIENT_NAME_BUSINESS
        ) {
            return false;
        }

        $this->typeClientName = $typeClientName;

        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getId();
    }
}