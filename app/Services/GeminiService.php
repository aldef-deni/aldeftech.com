<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;
use JsonException;

class GeminiService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->apiKey = (string) config('services.gemini.api_key');
        $this->model = (string) config('services.gemini.model');
        $this->baseUrl = rtrim(
            (string) config('services.gemini.base_url'),
            '/'
        );
        $this->timeout = (int) config('services.gemini.timeout', 120);

        if (empty($this->apiKey)) {
            throw new RuntimeException(
                'GEMINI_API_KEY belum dikonfigurasi.'
            );
        }

        if (empty($this->model)) {
            throw new RuntimeException(
                'GEMINI_MODEL belum dikonfigurasi.'
            );
        }
    }

    public function generate(string $prompt): string
    {
        $response = $this->request([
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        [
                            'text' => $prompt,
                        ],
                    ],
                ],
            ],

            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 8192,
            ],
        ]);

        $text = data_get(
            $response,
            'candidates.0.content.parts.0.text'
        );

        if (!is_string($text) || trim($text) === '') {
            throw new RuntimeException(
                'Gemini tidak mengembalikan response text.'
            );
        }

        return trim($text);
    }

    public function generateJson(
        string $prompt,
        array $schema = [],
        int $maxOutputTokens = 16384
    ): array {
        $generationConfig = [
            'temperature' => 0.7,
            'maxOutputTokens' => min(16384, max(256, $maxOutputTokens)),
            'responseMimeType' => 'application/json',
        ];

        if (!empty($schema)) {
            $generationConfig['responseSchema'] = $schema;
        }

        $response = $this->request([
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        [
                            'text' => $prompt,
                        ],
                    ],
                ],
            ],

            'generationConfig' => $generationConfig,
        ]);

        $text = data_get(
            $response,
            'candidates.0.content.parts.0.text'
        );

        if (!is_string($text) || trim($text) === '') {
            throw new RuntimeException(
                'Gemini tidak mengembalikan JSON.'
            );
        }

        try {
            $decoded = json_decode(
                $text,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            throw new RuntimeException(
                'JSON Gemini tidak valid.'
            );
        }

        if (!is_array($decoded) || array_is_list($decoded)) {
            throw new RuntimeException('JSON Gemini harus berupa objek artikel.');
        }

        return $decoded;
    }

    public function search(string $prompt): array
    {
        $response = $this->request([
            'contents' => [['role' => 'user', 'parts' => [['text' => $prompt]]]],
            'tools' => [['google_search' => (object) []]],
            'generationConfig' => ['temperature' => 0.2, 'maxOutputTokens' => 4096],
        ]);
        $metadata = data_get($response, 'candidates.0.groundingMetadata', []);
        if (empty($metadata['groundingChunks']) || empty($metadata['groundingSupports'])) {
            throw new RuntimeException('Sumber pencarian terverifikasi belum tersedia.');
        }

        return [
            'text' => collect(data_get($response, 'candidates.0.content.parts', []))->pluck('text')->filter()->implode("\n"),
            'metadata' => $metadata,
        ];
    }

    public function generateImage(string $prompt): string
    {
        $response = $this->request([
            'contents' => [['role' => 'user', 'parts' => [['text' => $prompt]]]],
            'generationConfig' => [
                'responseModalities' => ['TEXT', 'IMAGE'],
                'imageConfig' => ['aspectRatio' => '16:9'],
            ],
        ], (string) config('services.gemini.image_model', 'gemini-2.5-flash-image'));

        foreach (data_get($response, 'candidates.0.content.parts', []) as $part) {
            $data = $part['inlineData'] ?? [];
            if (! in_array($data['mimeType'] ?? '', ['image/png', 'image/jpeg', 'image/webp'], true)) {
                continue;
            }
            if (! is_string($data['data'] ?? null) || strlen($data['data']) > 28000000) {
                continue;
            }
            $bytes = base64_decode($data['data'], true);
            if ($bytes !== false && $bytes !== '') {
                return $bytes;
            }
        }
        throw new RuntimeException('Gemini tidak mengembalikan gambar.');
    }

    protected function request(array $payload, ?string $model = null): array
    {
        $url = sprintf(
            '%s/models/%s:generateContent',
            $this->baseUrl,
            $model ?? $this->model
        );

        $response = Http::connectTimeout(15)->timeout(min(180, max(1, $this->timeout)))
            ->acceptJson()
            ->withHeaders([
                'x-goog-api-key' => $this->apiKey,
            ])
            ->post($url, $payload);

        if ($response->failed()) {
            throw new RuntimeException(
                'Gemini API Error [' .
                $response->status() .
                ']. Coba lagi nanti.'
            );
        }

        $result = $response->json();
        if (!is_array($result)) {
            throw new RuntimeException('Respons Gemini tidak valid.');
        }

        if (data_get($result, 'candidates.0.finishReason') !== 'STOP') {
            throw new RuntimeException('Gemini belum menghasilkan artikel lengkap.');
        }

        return $result;
    }
}
