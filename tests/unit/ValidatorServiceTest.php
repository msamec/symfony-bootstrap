<?php namespace App\Tests;

use App\Entity\User;
use App\Exception\ValidationException;
use App\Service\ValidatorService;
use Codeception\AssertThrows;
use Codeception\Stub\Expected;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorServiceTest extends \Codeception\Test\Unit
{
    use AssertThrows;

    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testValidationPasses()
    {
        $validator = $this->makeEmpty(
            ValidatorInterface::class,
            [
                'validate' => Expected::atLeastOnce(function () {
                    return new ConstraintViolationList();
                })
            ]
        );

        $validationService = new ValidatorService($validator);
        $validationService->validate(new \stdClass());
    }

    public function testValidationFails()
    {
        $validator = $this->makeEmpty(
            ValidatorInterface::class,
            [
                'validate' => Expected::atLeastOnce(function () {
                    return new ConstraintViolationList(array(
                        new ConstraintViolation('a', 'b', array(), 'c', 'd', 'e', null, 'f'),
                    ));
                })
            ]
        );

        $validationService = new ValidatorService($validator);
        $this->assertThrowsWithMessage(
            ValidationException::class,
            'Validation failed',
            function () use ($validationService) {
                $validationService->validate(new \stdClass());
            }
        );
    }
}

