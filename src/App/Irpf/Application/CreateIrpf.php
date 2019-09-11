<?php
namespace CTIC\App\Irpf\Application;

use CTIC\App\Company\Domain\Company;
use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Irpf\Domain\Command\IrpfCommand;
use CTIC\App\Irpf\Domain\Irpf;

class CreateIrpf implements CreateInterface
{
    /**
     * @param CommandInterface|IrpfCommand $command
     * @return EntityInterface|Irpf
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $irpf = new Irpf();
        $irpf->setId($command->id);
        $irpf->name = (empty($command->name))? '' : $command->name;
        $irpf->enabled = (empty($command->enabled))? false : true;
        $irpf->irpf = (empty($command->irpf))? 0 : $command->irpf;
        if (!empty($command->company) && get_class($command->company) == Company::class) {
            $irpf->setCompany($command->company);
        }

        return $irpf;
    }
}