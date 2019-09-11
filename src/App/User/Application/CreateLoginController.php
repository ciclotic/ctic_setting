<?php
namespace CTIC\App\User\Application;


use CTIC\App\User\Infrastructure\Controller\LoginController;

class CreateLoginController extends CreateUserController
{
    public static function getResourceControllerClass(): string
    {
        return LoginController::class;
    }
}