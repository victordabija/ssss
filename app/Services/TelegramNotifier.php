<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class TelegramNotifier
{
    public const MARKDOWN_V2 = 'MarkdownV2';

    public const HTML = 'HTML';

    private string $token;

    private string $chatId;

    private string $parseMode;

    public function __construct()
    {
        if (
            blank(config('services.telegram.token')) ||
            blank(config('services.telegram.chat_id')) ||
            blank(config('services.telegram.parse_mode'))
        ) {
            throw new RuntimeException('Telegram notifier is not configured.');
        }

        $this->chatId = config('services.telegram.chat_id');
        $this->token = config('services.telegram.token');
        $this->parseMode = config('services.telegram.parse_mode');
    }

    public function send(string $message): void
    {
        $response = Http::post('https://api.telegram.org/bot' . $this->token . '/sendMessage', [
            'chat_id' => $this->chatId,
            'text' => $this->escape($message),
            'parse_mode' => $this->parseMode
        ]);

        if ($response->failed()) {
            Log::error("Telegram notifier failed to send message: {$response->body()}");
        }
    }

    private function escape(string $text): string
    {
        return match ($this->parseMode) {
            self::MARKDOWN_V2 => $this->escapeMarkdownV2($text),
            self::HTML => trim($text),
            default => throw new RuntimeException('Parse mode not supported.'),
        };
    }

    function escapeMarkdownV2(string $text): string
    {
        $escape_chars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
        foreach ($escape_chars as $char) {
            $text = str_replace($char, '\\' . $char, $text);
        }

        return $text;
    }
}
