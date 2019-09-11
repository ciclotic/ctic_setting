<?php
namespace CTIC\App\Iva\Application;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Iva\Domain\Command\IvaCommand;
use CTIC\App\Iva\Domain\Iva;

class CreateIva implements CreateInterface
{
    /**
     * @param CommandInterface|IvaCommand $command
     * @return EntityInterface|Iva
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $iva = new Iva();
        $iva->setId($command->id);
        $iva->name = (empty($command->name))? '' : $command->name;
        $iva->enabled = (empty($command->enabled))? false : true;
        $iva->iva = (empty($command->iva))? 0 : $command->iva;
        $iva->equivalenceProcurement = (empty($command->equivalenceProcurement))? 0 : $command->equivalenceProcurement;
        if (!empty($command->company) && get_class($command->company) == Company::class) {
            $iva->setCompany($command->company);
        }

        return $iva;
    }
}