<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductValidator extends ConstraintValidator
{
    public function validate($product, Constraint $constraint)
    {
        if ($product->getStock() < 0) {
            $this->context->buildViolation($constraint->message)
                ->atPath('stock')
                ->addViolation();
        }

        $category = $product->getCategory();
        if (!$category->isEnabled()) {
            $this->context->buildViolation('Doit être active')
                ->atPath('category')
                ->addViolation();
        }
    }
}
