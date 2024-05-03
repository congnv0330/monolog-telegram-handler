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
        /** @var string[] $content */
        $content = [];

        $content[] = "[{$record->datetime->format('Y-m-d H:i:s')}] {$record->channel}.{$record->level->getName()}: {$record->message}\n";

        /** @var \Exception $exception */
        $exception = $record->context['exception'];

        $exceptionClass = get_class($exception);

        $content[] = "<strong>{$exceptionClass}</strong>";
        $content[] = "<strong>File:</strong> {$exception->getFile()}:{$exception->getLine()}\n";

        $content[] = '<strong>Stack</strong>';

        /** @var array{file: string, line: int}[] $trace */
        $trace = array_slice($exception->getTrace(), 0, $maxStackLine);

        foreach ($trace as $index => $line) {
            $content[] = "#{$index} {$line['file']}:{$line['line']}";
        }

        $content[] = '...';

        return implode(PHP_EOL, $content);
    }
}
