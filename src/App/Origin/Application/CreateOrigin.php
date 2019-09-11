<?php
namespace CTIC\App\Origin\Application;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Origin\Domain\Command\OriginCommand;
use CTIC\App\Origin\Domain\Origin;

class CreateOrigin implements CreateInterface
{
    /**
     * @param CommandInterface|OriginCommand $command
     * @return EntityInterface|Origin
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $origin = new Origin();
        $origin->setId($command->id);
        $origin->name = (empty($command->name))? '' : $command->name;
        $origin->enabled = (empty($command->enabled))? false : true;
        if (!empty($command->company) && get_class($command->company) == Company::class) {
            $origin->setCompany($command->company);
        }

        return $origin;
    }
}