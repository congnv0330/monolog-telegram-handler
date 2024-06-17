<?php

declare(strict_types=1);

namespace TelegramLog;

use Monolog\LogRecord;

class TelegramMessageFormatter implements TelegramMessageFormatterInterface
{
    /**
     * @inheritDoc
     */
    public function format(LogRecord $record, int $maxStackLine): string
    {
        $eol = PHP_EOL;

        /** @var string[] $content */
        $content = [];

        $content[] = "[{$record->datetime->format('Y-m-d H:i:s')}] {$record->channel}.{$record->level->getName()}: {$record->message}{$eol}";

        /** @var \Exception $exception */
        $exception = $record->context['exception'];

        $exceptionClass = get_class($exception);

        $content[] = "<strong>{$exceptionClass}</strong>{$eol}";
        $content[] = "<strong>File:</strong> {$exception->getFile()}:{$exception->getLine()}{$eol}";

        $content[] = '<strong>[Stack]</strong>';

        $needSliceTrace = count($exception->getTrace()) > $maxStackLine;

        $trace = $needSliceTrace
            ? array_slice($exception->getTrace(), 0, $maxStackLine)
            : $exception->getTrace();

        /** @var array{file: string, line: int}[] $trace */
        $trace = array_slice($exception->getTrace(), 0, $maxStackLine);

        foreach ($trace as $index => $line) {
            $content[] = "#{$index} {$line['file']}:{$line['line']}";
        }

        if ($needSliceTrace) {
            $content[] = '...';
        }

        return implode($eol, $content);
    }
}
