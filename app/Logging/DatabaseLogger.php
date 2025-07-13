<?php

namespace App\Logging;

use Monolog\Logger;
use App\Logging\DatabaseLogHandler;

class DatabaseLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $handler = new DatabaseLogHandler($config, Logger::INFO);

        return new Logger('database', [$handler]);
    }
}
