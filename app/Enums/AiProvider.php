<?php

namespace App\Enums;

/**
 * Перечисление поддерживаемых AI-провайдеров для анализа кода.
 */
enum AiProvider: string
{
    case OPENAI = 'openai';
    case ANTHROPIC = 'anthropic';
    case GEMINI = 'gemini';
    case DEEPSEEK = 'deepseek';
    case CUSTOM = 'custom';

    /**
     * Человекочитаемый заголовок для конкретного провайдера.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::OPENAI => 'OpenAI GPT',
            self::ANTHROPIC => 'Anthropic Claude',
            self::GEMINI => 'Google Gemini',
            self::DEEPSEEK => 'DeepSeek',
            self::CUSTOM => 'Custom Model',
        };
    }

    /**
     * Массив доступных значений перечисления.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
