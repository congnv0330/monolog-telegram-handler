# Telegram Logging Handler

Telegram handler for Monolog.

## Install
```
composer require congnv/monolog-telegram-handler
```

## Declaring handler object

To declare this handler, you need to know the bot token and the chat identifier(chat_id) to which the log will be sent.

```
$handler = new \TelegramLog\TelegramHandler('<token>', '<chat_id>');
```

## Using with Laravel

Add telegram channel to `config/logging.php`

```php
'telegram' => [
    'driver'  => 'custom',
    'via' => \TelegramLog\TelegramLogger::class,
    'level' => env('LOG_LEVEL', 'debug'),
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'chat_id' => env('TELEGRAM_CHAT_ID'),
    // 'max_stack_line' => 10,
],
```

## Custom message formatter

Create new class implements \TelegramLog\TelegramMessageFormatterInterface

Example 
```php
<?php

namespace App\Logging\Formatter;

use Monolog\LogRecord;
use TelegramLog\TelegramMessageFormatterInterface;

class TelegramMessageFormatter implements TelegramMessageFormatterInterface
{
    public function format(LogRecord $record, int $maxStackLine): string
    {
        return [{$record->datetime->format('Y-m-d H:i:s')}] {$record->message}";
    }
}

```

Modify telegram channel

```php
'telegram' => [
    'driver'  => 'custom',
    'via' => \TelegramLog\TelegramLogger::class,
    'formatter' => \App\Logging\Formatter\TelegramMessageFormatter::class,
    'level' => env('LOG_LEVEL', 'debug'),
    'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    'chat_id' => env('TELEGRAM_CHAT_ID'),
    // 'max_stack_line' => 10,
],
```
