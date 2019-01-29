<?php

namespace App\Service;

use App\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ValidatorService.
 */
final class ValidatorService
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * ValidatorService constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(
        ValidatorInterface $validator
    ) {
        $this->validator = $validator;
    }

    /**
     * @param mixed $object
     * @param array $groups
     *
     * @throws ValidationException
     */
    public function validate($object, array $groups = []): void
    {
        $errors = [];

        if (\count($validationErrors = $this->validator->validate($object, null, $groups)) > 0) {
            /** @var ConstraintViolation $error */
            foreach ($validationErrors as $error) {
                $property = (string) string($error->getPropertyPath())->toSnakeCase();
                $errors[$property] = [
                    'message' => $error->getMessage(),
                    'description' => $error->getMessageTemplate(),
                ];
            }

            throw new ValidationException('Validation failed', $errors);
        }
    }
}
