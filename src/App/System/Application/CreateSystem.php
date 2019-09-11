<?php
namespace CTIC\App\System\Application;

use CTIC\App\Account\Application\CreateAccount;
use CTIC\App\Account\Domain\Account;
use CTIC\App\Account\Domain\Command\AccountCommand;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\System\Domain\Command\SystemCommand;
use CTIC\App\System\Domain\System;

class CreateSystem implements CreateInterface
{
    /**
     * @param CommandInterface|SystemCommand $command
     * @return EntityInterface|System
     *
     * @throws
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $system = new System();
        $system->setId($command->id);
        $system->name = (empty($command->name))? '' : $command->name;
        $system->enabled = (empty($command->enabled))? false : true;

        if (!$system->setStyleTPV($command->styleTPV)) {
            throw new \Exception('El estilo de TPV no está permitido.');
        }

        $system->enabledTPVSound = $command->enabledTPVSound;
        $system->enabledRoundDiscount = $command->enabledRoundDiscount;
        $system->numberTPVFamilyLines = $command->numberTPVFamilyLines;
        $system->numberTPVProductLines = $command->numberTPVProductLines;
        $system->enabledTPVAutomaticComplements = $command->enabledTPVAutomaticComplements;

        if (!$system->setTypeTPVPrint($command->typeTPVPrint)) {
            throw new \Exception('El tipo de impresión de la TPV no está permitido.');
        }

        if (!$system->setTypeClientName($command->typeClientName)) {
            throw new \Exception('El tipo de nombre de cliente no está permitido.');
        }

        return $system;
    }
}