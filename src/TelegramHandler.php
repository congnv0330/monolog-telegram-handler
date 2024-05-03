<?php

declare(strict_types=1);

namespace TelegramLog;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\Curl;
use Monolog\Handler\MissingExtensionException;
use Monolog\Level;
use Monolog\LogRecord;
use Monolog\Utils;

class TelegramHandler extends AbstractProcessingHandler
{
    /**
     * Telegram Bot API Url
     */
    private string $botApiUrl;

    /**
     * Telegram Chat ID
     */
    private string $chatId;

    /**
     * Max print error stack line
     */
    private int $maxStackLine;

    /**
     * Message Formatter
     */
    private TelegramMessageFormatterInterface $messageFormatter;

    /**
     * @param string $botToken
     * @param string $chatId
     * @param int $maxStackLine
     * @param class-string<TelegramMessageFormatterInterface> $messageFormatter
     * @param Level $level
     * @param bool $bubble
     *
     * @throws MissingExtensionException If the curl extension is missing
     */
    public function __construct(
        string $botToken,
        string $chatId,
        int $maxStackLine = 10,
        string $messageFormatter = TelegramMessageFormatter::class,
        Level $level = Level::Critical,
        bool $bubble = true,
    ) {
        if (!extension_loaded('curl')) {
            throw new MissingExtensionException('The curl extension is needed to use the TelegramHandler');
        }

        parent::__construct($level, $bubble);

        $this->botApiUrl = "https://api.telegram.org/bot{$botToken}";

        $this->chatId = $chatId;

        $this->maxStackLine = $maxStackLine;

        $this->messageFormatter = new $messageFormatter();
    }

    /**
     * @inheritDoc
     */
    protected function write(LogRecord $record): void
    {
        $data = [
            'chat_id' => $this->chatId,
            'text' => $this->messageFormatter->format($record, $this->maxStackLine),
            'parse_mode' => 'HTML',
        ];

        $postString = Utils::jsonEncode($data);

        $ch = curl_init();

        $options = [
            CURLOPT_URL => "{$this->botApiUrl}/sendMessage",
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-type: application/json'],
            CURLOPT_POSTFIELDS => $postString,
        ];

        curl_setopt_array($ch, $options);

        Curl\Util::execute($ch);
    }
}
