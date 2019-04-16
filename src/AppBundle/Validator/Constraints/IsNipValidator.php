<?php


namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;


class IsNipValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IsNip) {
            throw new UnexpectedTypeException($constraint, IsNip::class);
        }

        if (strlen($value) === 10) {
            $checkVal = ((int)$value[0] * 6 + (int)$value[1] * 5 + (int)$value[2] * 7 + (int)$value[3] * 2
                    + (int)$value[4] * 3 + (int)$value[5] * 4 + (int)$value[6] * 5 + (int)$value[7] * 6
                    + (int)$value[8] * 7) % 11;
            if ($checkVal === (int)$value[9]) {
                return true;
            }
        }

        if (!preg_match('/^0-9]+$/', $value, $matches)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}