<?php
namespace CTIC\App\Rate\Application;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Rate\Domain\Command\RateCommand;
use CTIC\App\Rate\Domain\Rate;

class CreateRate implements CreateInterface
{
    /**
     * @param CommandInterface|RateCommand $command
     * @return EntityInterface|Rate
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $rate = new Rate();
        $rate->setId($command->id);
        $rate->name = (empty($command->name))? '' : $command->name;
        $rate->enabled = (empty($command->enabled))? false : true;
        if (!empty($command->company) && get_class($command->company) == Company::class) {
            $rate->setCompany($command->company);
        }

        return $rate;
    }
}