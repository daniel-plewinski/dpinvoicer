<?php


namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsNip extends Constraint
{
    public $message = 'Numer NIP jest nieprawidłowy.';
}