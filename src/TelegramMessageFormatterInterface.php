<?php

declare(strict_types=1);

namespace TelegramLog;

use Monolog\LogRecord;

interface TelegramMessageFormatterInterface
{
    /**
     * Format exception data
     *
     * @param LogRecord $record
     * @param integer $maxStackLine
     * @return string
     */
    public function format(LogRecord $record, int $maxStackLine): string;
}
