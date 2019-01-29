<?php

namespace App\EventSubscriber;

use App\Exception\ValidationException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ExceptionListener
{
    /**
     * Debug mode
     *
     * @var bool
     */
    private $debug;
    /**
     * @var \Exception
     */
    private $exception;

    private $response = [];
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $apiLogger, $debug = false)
    {
        $this->logger = $apiLogger;
        $this->debug = $debug;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->exception = $event->getException();
        $className = get_class($this->exception);

        switch ($className) {
            case NotFoundHttpException::class:
                $this->error404();
                break;
            case ValidationException::class:
                $this->validation();
                break;
            case AccessDeniedHttpException::class:
                $this->accessDenied();
                break;
            default:
                $this->default();
        }

        if ($this->debug) {
            $this->response['debug'] = [
                'line' => $this->exception->getLine(),
                'file' => $this->exception->getFile(),
                'trace' => $this->exception->getTrace(),
            ];
        }

        $this->logger->error($this->exception->getMessage(), ['exception' => (array) $this->exception]);

        $event->setResponse(new JsonResponse($this->response, $this->response['code']));
    }

    private function error404(): void
    {
        /** @var NotFoundHttpException $exception */
        $exception = $this->exception;
        $this->response = [
            'message' => $exception->getMessage(),
            'code' => $exception->getStatusCode()
        ];
    }

    private function validation(): void
    {
        $this->default();

        /** @var ValidationException $exception */
        $exception = $this->exception;
        $this->response['errors'] = $exception->extraData;
    }

    private function accessDenied()
    {
        /** @var AccessDeniedHttpException $exception */
        $exception = $this->exception;
        $this->response = [
            'message' => $exception->getMessage(),
            'code' => $exception->getStatusCode()
        ];
    }

    private function default(): void
    {
        $this->response = [
            'message' => $this->exception->getMessage(),
            'code' => $this->exception->getCode() === 0 ? Response::HTTP_BAD_REQUEST : $this->exception->getCode()
        ];
    }
}
