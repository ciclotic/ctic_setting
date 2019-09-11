<?php
namespace CTIC\App\RealizationArea\Application;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\RealizationArea\Domain\Command\RealizationAreaCommand;
use CTIC\App\RealizationArea\Domain\RealizationArea;

class CreateRealizationArea implements CreateInterface
{
    /**
     * @param CommandInterface|RealizationAreaCommand $command
     * @return EntityInterface|RealizationArea
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $realizationArea = new RealizationArea();
        $realizationArea->setId($command->id);
        $realizationArea->name = (empty($command->name))? '' : $command->name;
        $realizationArea->enabled = (empty($command->enabled))? false : true;
        if (!empty($command->company) && get_class($command->company) == Company::class) {
            $realizationArea->setCompany($command->company);
        }

        return $realizationArea;
    }
}