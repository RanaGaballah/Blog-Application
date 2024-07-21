<?php

namespace App\Exceptions;

use App\Http\Resources\ErrorResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        JsonResource::withoutWrapping();

        if ($request->wantsJson()) {
            if ($exception instanceof ValidationException) {
                return response()->json(new ErrorResource(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    'Validation Error',
                    $exception->errors()
                ), Response::HTTP_UNPROCESSABLE_ENTITY);
            } elseif ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json(new ErrorResource(
                    Response::HTTP_METHOD_NOT_ALLOWED,
                    'Route method not allowed',
                    $exception->getMessage()
                ), Response::HTTP_METHOD_NOT_ALLOWED);
            } elseif ($exception instanceof NotFoundHttpException) {
                return response()->json(new ErrorResource(
                    Response::HTTP_NOT_FOUND,
                    'Route not found',
                    $exception->getMessage() ?: 'Route not found'
                ), Response::HTTP_NOT_FOUND);
            } elseif ($exception instanceof RouteNotFoundException) {
                return response()->json(new ErrorResource(
                    Response::HTTP_NOT_FOUND,
                    'Route not defined',
                    $exception->getMessage()
                ), Response::HTTP_NOT_FOUND);
            } elseif ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
                $modelName = class_basename($exception->getModel());
                return response()->json(new ErrorResource(
                    Response::HTTP_NOT_FOUND,
                    $modelName ." not found",
                    ['target' => $modelName ." not found"]
                ), Response::HTTP_NOT_FOUND);
            } elseif ($exception instanceof AuthorizationException) {
                return response()->json(new ErrorResource(
                    Response::HTTP_FORBIDDEN,
                    __('errors.forbidden')
                ), Response::HTTP_FORBIDDEN);
            } else {
                return response()->json(new ErrorResource(
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    'An unexpected error occurred.',
                    $exception->getMessage()
                ), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return parent::render($request, $exception);
    }
}
