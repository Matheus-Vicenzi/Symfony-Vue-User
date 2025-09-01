<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Psr\Log\LoggerInterface;

class ExceptionListener
{
    public function __construct(
        private LoggerInterface $logger,
        private string $environment
    ) {}

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();

        // Log da exceção
        $this->logger->error('Exception caught', [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);

        // Verificar se é uma requisição para API
        if (str_starts_with($request->getPathInfo(), '/api')) {
            $response = $this->createApiErrorResponse($exception);
            $event->setResponse($response);
        }
    }

    private function createApiErrorResponse(\Throwable $exception): JsonResponse
    {
        $statusCode = 500;
        $message = 'Erro interno do servidor';

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage();
        }

        [$statusCode, $data] = match (true) {
            $exception instanceof \InvalidArgumentException => [400, [
                'error' => 'Dados inválidos',
                'message' => $exception->getMessage(),
                'code' => 400
            ]],
            $exception instanceof \DomainException => [400, [
                'error' => 'Erro de negócio',
                'message' => $exception->getMessage(),
                'code' => 400
            ]],
            default => [$statusCode, [
                'error' => $message,
                'code' => $statusCode
            ]]
        };

        if ($this->environment === 'dev') {
            $data['debug'] = [
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => explode("\n", $exception->getTraceAsString())
            ];
        }

        return new JsonResponse($data, $statusCode);
    }
}
