<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class PostalCodeByCountry extends Constraint
{
    public string $message = 'Le code postal "{{ postalCode }}" n\'est pas valide pour le pays "{{ country }}".';
}
