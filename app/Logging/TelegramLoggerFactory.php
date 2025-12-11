<?php

namespace App\Logging;

use Monolog\Level;
use Monolog\Logger;

/**
 * Фабрика для создания Telegram логгера
 * Регистрируется в config/logging.php
 */
readonly class TelegramLoggerFactory
{
    /**
     * @param array $config
     * @return Logger
     */
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('telegram');
        $level = is_string($config['level'])
            ? Level::fromName($config['level'])
            : $config['level'];

        // Добавляем обработчик с параметрами из конфига
        $logger->pushHandler(new TelegramLoggerHandler(
            $config['bot_token'],
            $config['chat_id'],
            $level,
            $config['timeout']
        ));

        return $logger;
    }
}
