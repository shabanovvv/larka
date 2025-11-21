<?php

namespace App\Enums;

enum AiProvider: string
{
    case OPENAI = 'openai';
    case ANTHROPIC = 'anthropic';
    case GEMINI = 'gemini';
    case CUSTOM = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::OPENAI => 'OpenAI GPT',
            self::ANTHROPIC => 'Anthropic Claude',
            self::GEMINI => 'Google Gemini',
            self::CUSTOM => 'Custom Model',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
