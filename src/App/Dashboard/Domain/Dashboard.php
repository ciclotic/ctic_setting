<?php
namespace CTIC\App\Dashboard\Domain;

use Doctrine\ORM\Mapping as ORM;
use CTIC\App\Base\Domain\IdentifiableTrait;
use CTIC\App\Dashboard\Domain\Validation\DashboardValidation;

/**
 * @ORM\Entity(repositoryClass="CTIC\App\Dashboard\Infrastructure\Repository\DashboardRepository")
 */
class Dashboard implements DashboardInterface
{
    use IdentifiableTrait;
    use DashboardValidation;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    public $name;
}