<?php


namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Entity\Address;

class PostalCodeByCountryValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\Constraints\PostalCodeByCountry */
        if (null === $value || '' === $value) {
            // Le code postal vide est accepté si le champ est nullable
            return;
        }
        $object = $this->context->getObject();
        $country = null;
        if ($object instanceof Address) {
            $country = $object->getCountry();
        }

        // Quelques formats courants, tu peux étendre selon tes besoins
        $patterns = [
            'FR' => '/^\d{5}$/',
            'BE' => '/^\d{4}$/',
            'US' => '/^\d{5}(-\d{4})?$/',
            'GB' => '/^([A-Z]{1,2}[0-9R][0-9A-Z]? ?[0-9][ABD-HJLNP-UW-Z]{2}|GIR 0AA)$/i',
            // Ajoute d'autres pays ici...
        ];

        // On prend le pays comme code ISO à 2 lettres (FR/BE/US...)
        $pattern = $patterns[$country] ?? '/^[A-Za-z0-9\- ]{3,10}$/'; // par défaut: format général

        if (!preg_match($pattern, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ postalCode }}', $value)
                ->setParameter('{{ country }}', $country ?? '')
                ->addViolation();
        }
    }
}
