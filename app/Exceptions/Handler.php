<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        $this->logRequest($request, $exception);
        if ($exception instanceof ValidatorException) {
            return $this->renderValidatorException($exception);
        }
        if ($exception instanceof ValidationException) {
            return $this->renderValidationException($exception);
        }
        return parent::render($request, $exception);
    }

    private function renderValidationException($exception): JsonResponse
    {
        $bag = $exception->validator->getMessageBag();
        return response()->json([
            'error' => true,
            'message' => implode(', ', $this->parseMessages($bag)),
            'errors' => $this->parseMessages($bag)
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function renderValidatorException($exception): JsonResponse
    {
        $bag = $exception->getMessageBag();
        return response()->json([
            'error' => true,
            'message' => implode(', ', $this->parseMessages($bag)),
            'errors' => $this->parseMessages($bag)
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function parseMessages($bag): array
    {
        $messages = [];

        if (is_object($bag)) {
            $bag = json_decode(json_encode($bag), true);
            foreach ($bag as $field) {
                foreach ($field as $m) {
                    $messages[] = $m;
                }
            }
        }

        return $messages;
    }

    public function unauthenticated($request, AuthenticationException $exception): JsonResponse|ResponseAlias
    {
        return response()->json(['error' => true,'message' => 'unauthenticated'],401);
    }

    /**
     * Loga informações do request + exceção
     */
    private function logRequest(Request $request, Throwable $exception): void
    {
        Log::error('exception', [
            'body'      => $request->except($this->dontFlash),
            'message'   => $exception->getMessage()
        ]);
    }
}
