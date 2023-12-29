<?php

namespace App\Exceptions;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
       $code = $exception->getCode();
       $message = $exception->getMessage();
    
       // Ensure the status code is in the valid range
       if ($code < 100 || $code > 599) {
           $code = 500;
       }
    
       return response()->json(['error' => $message], $code);
    }

}
