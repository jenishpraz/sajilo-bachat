<?php

namespace App\Models;

class SocialLink extends ContentModel
{
    public const PLATFORMS = [
        'facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'x' => 'X (Twitter)',
        'tiktok' => 'TikTok', 'linkedin' => 'LinkedIn', 'whatsapp' => 'WhatsApp', 'viber' => 'Viber',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
