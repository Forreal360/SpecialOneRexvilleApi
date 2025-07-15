<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Support\ActionResult;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        
        $api_reponse_service = new ActionResult(
            success: false
        );

        /**
         * Unauthenticated
         */
        $exceptions->render(function (RouteNotFoundException $e, Request $request) use ($api_reponse_service) {
            $response = ActionResult::error(
                message: trans('auth.unauthenticated'),
                statusCode: 401
            );

            return $response->toApiResponse();
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) use ($api_reponse_service) {
            $response = ActionResult::error(
                message: trans('validation.404'),
                statusCode: 404
            );

            return $response->toApiResponse();
        });

        $exceptions->render(function (Exception $e, Request $request) use ($api_reponse_service) {
            dd($e);
            $response = ActionResult::error(
                message: trans('validation.500'),
                statusCode: 500
            );
            return $response->toApiResponse();
        });


        $exceptions->render(function (\Throwable $e, Request $request) use ($api_reponse_service) {
            dd($e);
            $response = ActionResult::error(
                message: trans('validation.500'),
                statusCode: 500
            );
            return $response->toApiResponse();
        });

    })->create();
