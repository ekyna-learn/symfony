<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Product extends Constraint
{
    public $message = 'Invalid stock';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}