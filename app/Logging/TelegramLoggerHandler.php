<?php

namespace App\Logging;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

/**
 * Обработчик для отправки логов в Telegram
 */
class TelegramLoggerHandler extends AbstractProcessingHandler
{
    /**
     * @param string $botToken
     * @param string $chatId
     * @param int|string|Level $level
     * @param int $timeout
     * @param bool $bubble
     */
    public function __construct(private readonly string $botToken,
                                private readonly string $chatId,
                                int|string|Level        $level = Level::Debug,
                                private readonly int    $timeout,
                                bool                    $bubble = true
    )
    {
        parent::__construct($level, $bubble);
    }

    /**
     * Отправляет сообщение в Telegram через Bot API
     *
     * @param LogRecord $record
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        $message = $this->formatMessage($record);

        try {
            // Отправляем HTTP запрос к Telegram API
            $response = Http::timeout($this->timeout)
                ->post("https://api.telegram.org/bot{$this->botToken}/sendMessage", [
                    'chat_id' => $this->chatId,
                    'text' => $message,
                    'parse_mode' => 'HTML',
                ]
            );
            // Логируем ошибки API в файл
            if (!$response->successful()) {
                Log::channel('single')->error('Telegram API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Telegram log failed: ' . $e->getMessage());
        }
    }

    /**
     * Форматирует лог-запись в HTML для Telegram
     *
     * @param LogRecord $record
     * @return string
     */
    protected function formatMessage(LogRecord $record): string
    {
        $levelName = $record->level->getName();
        $emoji = $this->getEmoji($levelName);
        $message = "<b>Time:</b> " . $record->datetime->format('Y-m-d H:i:s') . "\n";
        $message .= "<b>Level:</b> {$emoji}{$levelName}\n";
        $message .= "<b>Message:</b> {$record->message}\n";
        if (!empty($record->context)) {
            $message .= "<b>Context:</b> <pre>" . json_encode($record->context, JSON_PRETTY_PRINT) . "</pre>";
        }

        return $message;
    }

    /**
     * Возвращает emoji для разных уровней логирования
     *
     * @param string $levelName
     * @return string
     */
    private function getEmoji(string $levelName): string
    {
        return match($levelName) {
            'WARNING' => '⚠️',
            'ERROR' => '❌',
            'CRITICAL', 'ALERT', 'EMERGENCY' => '🚨',
            default => 'ℹ️',
        };
    }
}
