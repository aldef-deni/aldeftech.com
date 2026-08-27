<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            return route('admin.login');
        });

        // Runs inside the web group so the session is already available.
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        /*
         * An upload bigger than post_max_size is rejected by ValidatePostSize,
         * which runs in the GLOBAL middleware stack — before StartSession. That
         * means the default 413 page is a bare PHP warning and, because there is
         * no session yet, a flash message would be thrown away. Send the user
         * back to the form with a query flag instead; layouts/sections/flash
         * turns it into a normal alert.
         */
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $message = 'Berkas melebihi batas ' . max_upload_label() . ' dan ditolak server sebelum sempat diproses.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }

            $back = $request->headers->get('referer') ?: url('/');
            $back .= (str_contains($back, '?') ? '&' : '?') . 'upload_error=too_large';

            return redirect()->to($back);
        });
    })->create();
