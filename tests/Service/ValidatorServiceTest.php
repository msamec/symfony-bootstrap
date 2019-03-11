<?php

use App\Service\ValidatorService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorServiceTest extends TestCase
{
    public function testValidate()
    {
        $validator = $this->createMock(ValidatorInterface::class);

        $validatorService = new ValidatorService(
            $validator
        );
    }
}
