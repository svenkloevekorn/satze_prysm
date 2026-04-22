<?php

namespace App\Enums;

enum RatingSource: string
{
    case ProductOrdered = 'product_ordered';
    case ProductWorn = 'product_worn';
    case ProductSeenOnline = 'product_seen_online';
    case Story = 'story';
    case ForumPosts = 'forum_posts';

    public function label(): string
    {
        return match ($this) {
            self::ProductOrdered => 'Produkt bestellt',
            self::ProductWorn => 'Produkt getragen',
            self::ProductSeenOnline => 'Produkt online gesehen',
            self::Story => 'Story / Erfahrungsbericht',
            self::ForumPosts => 'Forum posts',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::ProductOrdered => 'heroicon-o-shopping-cart',
            self::ProductWorn => 'heroicon-o-user',
            self::ProductSeenOnline => 'heroicon-o-eye',
            self::Story => 'heroicon-o-book-open',
            self::ForumPosts => 'heroicon-o-chat-bubble-left-right',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ProductOrdered => 'success',
            self::ProductWorn => 'primary',
            self::ProductSeenOnline => 'info',
            self::Story => 'warning',
            self::ForumPosts => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case) => [$case->value => $case->label()])
            ->toArray();
    }
}
