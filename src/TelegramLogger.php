<?php

declare(strict_types=1);

namespace TelegramLog;

use Monolog\Level;
use Monolog\Logger;
use TelegramLog\TelegramHandler;

class TelegramLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param array{
     *  bot_token: string,
     *  chat_id: string,
     *  max_stack_line?: int,
     *  formatter?: class-string<TelegramMessageFormatterInterface>,
     *  level?: Level,
     *  bubble?: bool
     * } $config
     *
     * @return Logger
     */
    public function __invoke(array $config): Logger
    {
        return new Logger(
            name: 'TelegramLogger',
            handlers: [
                new TelegramHandler(
                    botToken: $config['bot_token'],
                    chatId: $config['chat_id'],
                    maxStackLine: $config['max_stack_line'] ?? 10,
                    messageFormatter: $config['formatter'] ?? TelegramMessageFormatter::class,
                    level: $config['level'] ?? Level::Critical,
                    bubble: $config['bubble'] ?? true,
                ),
            ],
        );
    }
}
