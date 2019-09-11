<?php
namespace CTIC\App\Dashboard\Domain\Fixture;

use CTIC\App\Dashboard\Application\CreateDashboard;
use CTIC\App\Dashboard\Domain\Command\DashboardCommand;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class DashboardFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        $dashboardCommandDefault = new DashboardCommand();
        $dashboardCommandDefault->name = 'default';
        $dashboardDefault = CreateDashboard::create($dashboardCommandDefault);
        $manager->persist($dashboardDefault);

        $manager->flush();
    }
}