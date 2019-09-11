<?php

namespace CTIC\App\Company\Infrastructure\Controller;

use CTIC\App\Base\Domain\EntityInterface;
use CTIC\App\Base\Infrastructure\Controller\ResourceController;
use CTIC\App\Base\Infrastructure\Request\RequestConfiguration;
use CTIC\App\Company\Domain\Company;
use CTIC\App\Company\Domain\CompanyInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Yaml\Yaml;

class CompanyController extends ResourceController
{
    static function setCompanyData(Session $session, Company $company, Request $request, array $companies): void
    {
        $companiesToSession = array();
        /** @var Company $companyToSession */
        foreach ($companies as $companyToSession) {
            $newCompanyToSession = array();
            $newCompanyToSession['id'] = $companyToSession->getId();
            $newCompanyToSession['businessName'] = $companyToSession->getBusinessName();

            $companiesToSession[] = $newCompanyToSession;
        }

        $session->set('companies', $companiesToSession);
        $session->set('company', array(
            'id' => $company->getId(),
            'businessName' => $company->getBusinessName(),
            'address'  => $company->getAddress(),
            'postalCode'  => $company->getPostalCode(),
            'town'  => $company->getTown(),
            'country'  => $company->getCountry(),
            'smtpAliasName'  => $company->getSmtpAliasName(),
            'smtpEmail'  => $company->getSmtpEmail(),
            'smtpHost'  => $company->getSmtpHost(),
            'smtpPassword'  => $company->getSmtpPassword(),
            'taxIdentification'  => $company->getTaxIdentification(),
            'taxName'  => $company->getTaxName(),
            'ccc'  => $company->getCCC(),
            'administratorIdentification'  => $company->getAdministratorIdentification(),
            'administratorName'  => $company->getAdministratorName()
        ));
        $request->setSession($session);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws
     */
    public function switchAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        if(!$this->isGrantedOr403($configuration))
        {
            return $this->redirectToLogin($configuration);
        }
        /** @var Company $resource */
        $resource = $this->findOr404($configuration);

        if (!empty($resource)) {

            $session = new Session();
            $companies = $this->repository->findAll();
            self::setCompanyData($session, $resource, $request, $companies);
        }

        $configYaml = Yaml::parse(file_get_contents(__DIR__.'/../../../../../../config.yml'));
        $hostToLocation = $configYaml['modules']['human_resources']['host'];

        header('Location: http://' . $hostToLocation);
        exit();
    }

    /**
     * @param EntityInterface|Company $resource
     * @param RequestConfiguration $configuration
     */
    protected function completeEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $password = $resource->getSmtpPassword();

        if (!empty($password)) {
            $resource->smtpPassword = md5($password);
        }
    }

    /**
     * @param EntityInterface|Company $resource
     * @param RequestConfiguration $configuration
     */
    protected function completeCreateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $this->completeEntity($resource, $configuration);
    }

    /**
     * @param EntityInterface|Company $resource
     * @param RequestConfiguration $configuration
     */
    protected function completeUpdateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $this->completeEntity($resource, $configuration);
    }

    /**
     * @param EntityInterface|Company $resource
     * @param RequestConfiguration $configuration
     */
    protected function prepareUpdateEntity(EntityInterface $resource, RequestConfiguration $configuration): void
    {
        $resource->smtpPassword = null;
    }
}