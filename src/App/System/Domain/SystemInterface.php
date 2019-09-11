<?php
namespace CTIC\App\System\Domain;

use CTIC\App\Account\Domain\Account;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Domain\IdentifiableInterface;

interface SystemInterface extends IdentifiableInterface, EntityInterface
{
    const STYLE_TPV_DEFAULT = 0;

    const TYPE_TPV_PRINT_NPRINT_NCASH = 0;
    const TYPE_TPV_PRINT_PRINT_CASH = 1;
    const TYPE_TPV_PRINT_NPRINT_CASH = 2;
    const TYPE_TPV_PRINT_PRINT_NCASH = 3;

    const TYPE_TPV_CLIENT_NAME_TAX = 0;
    const TYPE_TPV_CLIENT_NAME_BUSINESS = 1;

    public function getName(): string;
    public function isEnabled(): bool;
    public function getStyleTPV(): int;
    public function setStyleTPV($styleTPV): bool;
    public function isEnabledTPVSound(): bool;
    public function isEnabledRoundDiscount(): bool;
    public function getNumberTPVFamilyLines(): int;
    public function getNumberTPVProductLines(): int;
    public function isEnabledTPVAutomaticComplements(): bool;
    public function getTypeTPVPrint(): int;
    public function setTypeTPVPrint($typeTPVPrint): bool;
    public function getTypeClientName(): int;
    public function setTypeClientName($typeClientName): bool;
}