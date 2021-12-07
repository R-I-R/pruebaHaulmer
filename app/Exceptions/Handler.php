<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

		$this->renderable(function (Throwable $e) {
			if($e instanceof MethodNotAllowedHttpException) {
				return response()->json(['error' => 'Method not allowed'], $e->getStatusCode());
			}
		});
    }

	protected function unauthenticated($request, AuthenticationException $exception){
    	return response()->json(['message' => $exception->getMessage()], 401);
  
	}
}
