<?php

namespace App\Logging;

use DB;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Throwable;

/**
 *
 */
class DatabaseLogHandler extends AbstractProcessingHandler
{
    public function __construct(array $config, int $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    protected function write( $record): void
    {
        $context = $record['context'];

        if (isset($record['context']['exception']) && $record['context']['exception'] instanceof Throwable) {
            $context['exception'] = $this->convertExceptionToArray($record['context']['exception']);
        }

        $created_by = auth()->user()->id ?? null;

        $data = [
            'tag' => env('APP_NAME'),
            'level' => $record['level_name'],
            'message' => $record['message'],
            'context' => json_encode($context),
            'ip' => request()->ip(),
            'method' => request()->getMethod(),
            'url' => request()->fullUrl(),
            'header' => json_encode(request()->header()),
            'body' => json_encode(request()->all()),
            'created_by' => $created_by ?? null,
            'created_at' => $record['datetime']->format('Y-m-d H:i:s'),
        ];

        DB::table(env('DB_LOG_TABLE'))->insert($data);
    }

    protected function convertExceptionToArray(Throwable $e)
    {
        if (method_exists($e, 'data')) {
            $data = $e->data();
        }

        return [
            'message' => $e->getMessage(),
            'exception' => get_class($e),
            'data' => $data ?? null,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ];
    }
}
