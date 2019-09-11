<?php

namespace CTIC\App\User\Infrastructure\Controller;

use CTIC\App\Base\Infrastructure\View\Rest\View;
use CTIC\App\User\Domain\User;
use Nette\Security\Identity;
use Nette\Security\Passwords;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginController extends UserController
{
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws
     */
    public function loginAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true)) {
            $username = $request->get('name');
            $password = $request->get('password');

            if ($username === null || $password === null) {
                return $this->redirectToLogin($configuration);
            }

            /** @var User $user */
            $user = $this->repository->findOneBy(array('name' => $username));
            if (empty($user)) {
                return $this->redirectToLogin($configuration);
            }

            if (md5($password) != $user->getPassword()) {
                return $this->redirectToLogin($configuration);
            }

            $identity = new Identity($user->getId(), $user->getPermission(), ['username' => $username, 'id' => $user->getId()]);
            $session = new Session();
            $session->set('identity', array(
                'id' => $identity->getId(),
                'roles' => $identity->getRoles(),
                'data'  => $identity->getData()
            ));
            $request->setSession($session);

            return $this->redirectHandler->redirectToRoute($configuration, 'dashboard');
        }

        $view = View::create();

        if ($configuration->isHtmlRequest()) {
            $view
                ->setTemplate($configuration->getTemplate('login.html'))
                ->setTemplateVar($this->metadata->getName())
                ->setData([
                    'configuration' => $configuration,
                    'metadata' => $this->metadata
                ])
            ;
        }

        return $this->viewHandler->handle($view, $configuration->getRequest());
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws
     */
    public function logoutAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $session = new Session();
        $session->remove('identity');
        $session->remove('dbHost');
        $session->remove('dbUser');
        $session->remove('dbPassword');
        $session->remove('dbName');
        $session->remove('company');
        $session->remove('companies');
        $request->setSession($session);

        return $this->redirectHandler->redirectToRoute($configuration, 'login');
    }
}