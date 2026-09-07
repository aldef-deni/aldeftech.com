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
        array $schema = []
    ): array {
        $generationConfig = [
            'temperature' => 0.7,
            'maxOutputTokens' => 16384,
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

    protected function request(array $payload): array
    {
        $url = sprintf(
            '%s/models/%s:generateContent',
            $this->baseUrl,
            $this->model
        );

        $response = Http::timeout($this->timeout)
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
