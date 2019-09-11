<?php
namespace CTIC\App\PaymentMethod\Domain\Validation;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;

trait PaymentMethodExpireValidation
{
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

    }
}