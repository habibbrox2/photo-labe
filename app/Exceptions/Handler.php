<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e)
    {
        if ($this->isParseError($e) || $this->isServerError($e)) {
            return $this->renderErrorPage($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Check if the exception is a parse error (syntax error in Blade templates).
     *
     * @param  \Throwable  $e
     * @return bool
     */
    protected function isParseError(Throwable $e): bool
    {
        return $e instanceof \ParseError
            || str_contains($e->getMessage(), 'ParseError')
            || str_contains(get_class($e), 'ParseError');
    }

    /**
     * Check if the exception is a server error (5xx).
     *
     * @param  \Throwable  $e
     * @return bool
     */
    protected function isServerError(Throwable $e): bool
    {
        $code = $e->getCode();
        return $code >= 500 && $code < 600;
    }

    /**
     * Render a custom error page with error details.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    protected function renderErrorPage($request, Throwable $e)
    {
        $errorDetails = $this->formatErrorForDisplay($e);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $errorDetails,
            ], 500);
        }

        return response()->view('errors.500', [
            'exception' => $e,
            'errorDetails' => $errorDetails,
        ], 500);
    }

    /**
     * Format the exception into a structured array for display.
     *
     * @param  \Throwable  $e
     * @return array
     */
    protected function formatErrorForDisplay(Throwable $e): array
    {
        $trace = collect($e->getTrace())->map(function ($frame, $index) {
            $file = $frame['file'] ?? 'unknown';
            $line = $frame['line'] ?? 0;
            $function = $frame['function'] ?? '';
            $args = collect($frame['args'] ?? [])->map(function ($arg) {
                if (is_object($arg)) {
                    return get_class($arg);
                }
                if (is_array($arg)) {
                    return 'array(' . count($arg) . ')';
                }
                if (is_string($arg) && strlen($arg) > 80) {
                    return substr($arg, 0, 80) . '...';
                }
                return var_export($arg, true);
            })->implode(', ');

            return [
                'number' => $index + 1,
                'file' => $file,
                'line' => $line,
                'function' => $function,
                'args' => $args,
            ];
        })->toArray();

        return [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $trace,
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
        ];
    }
}