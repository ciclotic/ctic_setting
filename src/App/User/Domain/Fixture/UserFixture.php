<?php
namespace CTIC\App\User\Domain\Fixture;

use CTIC\App\Account\Domain\Account;
use CTIC\App\Account\Infrastructure\Repository\AccountRepository;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use CTIC\App\User\Application\CreateUser;
use CTIC\App\User\Domain\Command\UserCommand;

class UserFixture extends AbstractFixture
{
    public function load(ObjectManager $manager)
    {
        /** @var AccountRepository $accountRepository
         */
        $accountRepository = $manager->getRepository(Account::class);
        $accountStroika = $accountRepository->findOneByName('cicloTIC');

        $userCommandValenti = new UserCommand();
        $userCommandValenti->name = 'valenti';
        $userCommandValenti->enabled = true;
        $userCommandValenti->password = '1234';
        $userCommandValenti->permission = 1;
        $userCommandValenti->account = $accountStroika;
        $userValenti = CreateUser::create($userCommandValenti);
        $manager->persist($userValenti);

        $userCommandToni = new UserCommand();
        $userCommandToni->name = 'toni';
        $userCommandToni->enabled = true;
        $userCommandToni->password = '12345';
        $userCommandToni->permission = 2;
        $userCommandToni->account = $accountStroika;
        $userToni= CreateUser::create($userCommandToni);
        $manager->persist($userToni);

        $manager->flush();
    }
}