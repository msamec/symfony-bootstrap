<?php

namespace App\Service;

use App\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/*final*/ class ValidatorService
{
    private $validator;

    public function __construct(
        ValidatorInterface $validator
    ) {
        $this->validator = $validator;
    }

    /**
     * @param mixed $object
     * @param array $groups
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate($object, array $groups = []): bool
    {
        $errors = [];

        if (\count($validationErrors = $this->validator->validate($object, null, $groups)) > 0) {
            /** @var ConstraintViolation $error */
            foreach ($validationErrors as $error) {
                $property = $error->getPropertyPath();
                $errors[$property] = [
                    'message' => $error->getMessage(),
                    'description' => $error->getMessageTemplate(),
                ];
            }

            throw new ValidationException('Validation failed', $errors);
        }

        return true;
    }
}
