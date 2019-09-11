<?php
namespace CTIC\App\Privacy\Application;

use CTIC\App\Base\Application\CreateInterface;
use CTIC\App\Base\Domain\Command\CommandInterface;
use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Privacy\Domain\Command\PrivacyCommand;
use CTIC\App\Privacy\Domain\Privacy;

class CreatePrivacy implements CreateInterface
{
    /**
     * @param CommandInterface|PrivacyCommand $command
     * @return EntityInterface|Privacy
     */
    public static function create(CommandInterface $command): EntityInterface
    {
        $privacy = new Privacy();
        $privacy->own = $command->own;
        //TODO $privacy->setParent($command->parent);

        return $privacy;
    }
}