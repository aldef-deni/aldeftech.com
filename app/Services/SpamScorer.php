<?php

namespace App\Services;

use Illuminate\Support\Str;

/**
 * Scores an incoming contact submission for how likely it is to be junk.
 *
 * Deliberately transparent: every signal returns a human-readable reason that
 * is stored alongside the score, so an editor can see why a lead was flagged
 * and correct it. A filter nobody can audit gets distrusted, then ignored, and
 * then real leads start getting missed.
 *
 * Nothing here deletes or rejects anything. A flagged lead is still saved and
 * still visible under the Spam filter — a false positive must cost a click,
 * never a lost sale.
 */
class SpamScorer
{
    /** At or above this, a submission is filed as spam. */
    public const THRESHOLD = 50;

    /** Anything faster than this is not a human reading a form. */
    private const MIN_SECONDS = 3;

    private const DISPOSABLE_DOMAINS = [
        'mailinator.com', 'guerrillamail.com', '10minutemail.com', 'tempmail.com',
        'yopmail.com', 'trashmail.com', 'sharklasers.com', 'getnada.com',
        'temp-mail.org', 'throwawaymail.com', 'maildrop.cc', 'dispostable.com',
    ];

    /** Pitches that arrive by the hundred and never become clients. */
    private const PITCH_PATTERNS = [
        'seo service', 'seo services', 'backlink', 'guest post', 'link building',
        'increase your ranking', 'first page of google', 'crypto', 'bitcoin',
        'forex', 'casino', 'loan offer', 'make money online', 'work from home',
        'cheap traffic', 'buy followers', 'web design services at',
    ];

    /**
     * @param  array<string, mixed>  $data      validated form fields
     * @param  int|null  $secondsOnForm         null when the timing field was absent or tampered with
     * @param  bool  $honeypotFilled            a field no human can see was filled in
     * @return array{score: int, reasons: array<int, string>}
     */
    public function score(array $data, ?int $secondsOnForm = null, bool $honeypotFilled = false): array
    {
        $reasons = [];
        $score = 0;

        $message = (string) ($data['message'] ?? '');
        $name = (string) ($data['name'] ?? '');
        $email = (string) ($data['email'] ?? '');
        $haystack = Str::lower($name . ' ' . $message . ' ' . ($data['company'] ?? ''));

        // A hidden field only an automated client would ever complete.
        if ($honeypotFilled) {
            $score += 100;
            $reasons[] = 'Mengisi kolom tersembunyi yang tidak terlihat manusia.';
        }

        // Decisive on its own: a person cannot type a name, an email and a
        // message in under three seconds, and password managers do not fill
        // free-text fields.
        if ($secondsOnForm !== null && $secondsOnForm < self::MIN_SECONDS) {
            $score += 55;
            $reasons[] = "Formulir dikirim dalam {$secondsOnForm} detik — terlalu cepat untuk diisi manusia.";
        }

        // Link-stuffing is the single most reliable signal in a brief form.
        $links = preg_match_all('~https?://|www\.|\[url|<a\s~i', $message);
        if ($links > 0) {
            $add = min(40, 20 * $links);
            $score += $add;
            $reasons[] = $links === 1
                ? 'Pesan memuat satu tautan.'
                : "Pesan memuat {$links} tautan.";
        }

        if (preg_match('~https?://|www\.~i', $name)) {
            $score += 40;
            $reasons[] = 'Kolom nama memuat tautan.';
        }

        // The site serves Indonesian and English; other scripts are a strong hint.
        if (preg_match('~[\x{0400}-\x{04FF}\x{4E00}-\x{9FFF}\x{0600}-\x{06FF}]~u', $message)) {
            $score += 35;
            $reasons[] = 'Pesan memuat aksara di luar Latin.';
        }

        $domain = Str::lower(Str::after($email, '@'));
        if ($domain && in_array($domain, self::DISPOSABLE_DOMAINS, true)) {
            $score += 30;
            $reasons[] = "Memakai email sekali pakai ({$domain}).";
        }

        foreach (self::PITCH_PATTERNS as $pattern) {
            if (str_contains($haystack, $pattern)) {
                $score += 30;
                $reasons[] = 'Menyerupai penawaran massal, bukan permintaan proyek.';
                break;
            }
        }

        // SHOUTING is common in blast templates and rare in a real enquiry.
        $letters = preg_replace('~[^a-zA-Z]~', '', $message);
        if (strlen($letters) >= 25) {
            $upper = strlen(preg_replace('~[^A-Z]~', '', $message));
            if ($upper / strlen($letters) > 0.7) {
                $score += 15;
                $reasons[] = 'Pesan hampir seluruhnya huruf kapital.';
            }
        }

        return ['score' => min(100, $score), 'reasons' => $reasons];
    }

    public function isSpam(int $score): bool
    {
        return $score >= self::THRESHOLD;
    }
}
