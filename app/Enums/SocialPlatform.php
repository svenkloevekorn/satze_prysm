<?php

namespace App\Enums;

enum SocialPlatform: string
{
    case Instagram = 'instagram';
    case TikTok = 'tiktok';
    case YouTube = 'youtube';
    case X = 'x';
    case LinkedIn = 'linkedin';
    case Facebook = 'facebook';

    public function label(): string
    {
        return match ($this) {
            self::Instagram => 'Instagram',
            self::TikTok => 'TikTok',
            self::YouTube => 'YouTube',
            self::X => 'X (Twitter)',
            self::LinkedIn => 'LinkedIn',
            self::Facebook => 'Facebook',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Instagram => 'heroicon-o-camera',
            self::TikTok => 'heroicon-o-musical-note',
            self::YouTube => 'heroicon-o-play',
            self::X => 'heroicon-o-hashtag',
            self::LinkedIn => 'heroicon-o-briefcase',
            self::Facebook => 'heroicon-o-chat-bubble-oval-left',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Instagram => 'warning',
            self::TikTok => 'gray',
            self::YouTube => 'danger',
            self::X => 'info',
            self::LinkedIn => 'primary',
            self::Facebook => 'info',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}
