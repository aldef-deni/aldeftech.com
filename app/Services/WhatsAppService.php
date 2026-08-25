<?php

namespace App\Services;

use App\Models\SiteSetting;

class WhatsAppService
{
    public static function getNumber(): string
    {
        return preg_replace(
            '/[^0-9]/',
            '',
            SiteSetting::get(
                'whatsapp_number',
                config('aldeftech.whatsapp.number', '')
            )
        );
    }

    public static function getMessage(): string
    {
        return SiteSetting::get(
            'whatsapp_default_message',
            config(
                'aldeftech.whatsapp.default_message',
                'Halo Aldef Tech, saya ingin berkonsultasi mengenai kebutuhan software/sistem untuk bisnis saya.'
            )
        );
    }

    public static function getUrl(?string $customMessage = null): string
    {
        $number = self::getNumber();

        $message = $customMessage !== null
            ? $customMessage
            : self::getMessage();

        return 'https://wa.me/' . $number . '?text=' . rawurlencode($message);
    }

    public static function getProjectUrl(string $projectType): string
    {
        $message = "Halo Aldef Tech, saya tertarik dengan layanan {$projectType}. Saya ingin berkonsultasi lebih lanjut.";

        return self::getUrl($message);
    }
}
