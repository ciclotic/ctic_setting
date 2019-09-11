<?php
declare(strict_types=1);

namespace CTIC\App\Company\Test\Application;

use CTIC\App\Company\Application\CreateCompany;
use CTIC\App\Company\Domain\Command\CompanyCommand;
use CTIC\App\Company\Domain\Company;
use PHPUnit\Framework\TestCase;

final class CreateCompanyTest extends TestCase
{
    public function testCreateAssert()
    {
        $companyCommandRyu = new CompanyCommand();
        $companyCommandRyu->name = 'ryu';
        $companyRyu = CreateCompany::create($companyCommandRyu);

        $this->assertEquals(Company::class, get_class($companyRyu));
    }
}