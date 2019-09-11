<?php
namespace CTIC\App\Dashboard\Application;

use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Dashboard\Domain\Command\DashboardCommand;
use CTIC\App\Dashboard\Domain\Dashboard;

class CreateDashboard implements CreateInterface
{
    /**
     * @param CommandInterface|DashboardCommand $command
     * @return EntityInterface|Dashboard
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $dashboard = new Dashboard();
        $dashboard->name = $command->name;

        return $dashboard;
    }
}