<?php

namespace App\Services;

use App\Models\SiteSetting;

class WhatsAppService
{
    public static function getNumber(): string
    {
        return SiteSetting::get('whatsapp_number', config('aldeftech.whatsapp.number', ''));
    }

    public static function getMessage(): string
    {
        return SiteSetting::get('whatsapp_default_message', config('aldeftech.whatsapp.default_message'));
    }

    public static function getUrl(?string $customMessage = null): string
    {
        $number = preg_replace('/[^0-9]/', '', self::getNumber());
        $message = urlencode($customMessage ?? self::getMessage());

        return "https://wa.me/{$number}?text={$message}";
    }

    public static function getProjectUrl(string $projectType): string
    {
        $message = "Hallo Aldef Tech, saya tertarik dengan layanan {$projectType}. Saya ingin berkonsultasi lebih lanjut.";
        return self::getUrl($message);
    }
}
