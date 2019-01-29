<?php

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class ValidationException.
 */
final class ValidationException extends \Exception
{
    /**
     * @var array
     */
    public $extraData;

    /**
     * ValidationException constructor.
     *
     * @param string $message
     * @param array  $extraData
     */
    public function __construct($message = '', array $extraData = [])
    {
        $this->extraData = $extraData;

        parent::__construct($message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
