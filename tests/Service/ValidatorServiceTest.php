<?php

use App\Exception\ValidationException;
use App\Service\ValidatorService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorServiceTest extends TestCase
{
    public function testIfValidationFails()
    {
        $validator = $this->createMock(ValidatorInterface::class);

        $validator->method('validate')
            ->will(
                $this->returnValue(
                    new ConstraintViolationList([
                        new ConstraintViolation('a', 'b', [], 'c', 'd', 'e', null, 'f')
                    ])
                )
            )
        ;

        $validatorService = new ValidatorService(
            $validator
        );

        $object = new stdClass();
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Validation failed');

        $validatorService->validate($object);
    }

    public function testIfValidationPasses()
    {
        $validator = $this->createMock(ValidatorInterface::class);

        $validator->method('validate')
            ->will(
                $this->returnValue(
                    new ConstraintViolationList()
                )
            )
        ;

        $validatorService = new ValidatorService(
            $validator
        );

        $object = new stdClass();
        $this->assertEquals(true, $validatorService->validate($object)) ;
    }
}
