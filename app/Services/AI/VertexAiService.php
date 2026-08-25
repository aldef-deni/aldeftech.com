<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class VertexAiService
{
    public function generateContent(string $prompt): string
    {
        $project = config('services.vertex_ai.project');
        $location = config('services.vertex_ai.location');
        $model = config('services.vertex_ai.model');
        $timeout = (int) config('services.vertex_ai.timeout', 120);

        if ($project && $location && $model) {
            return $this->generateViaVertex(
                $prompt,
                $project,
                $location,
                $model,
                $timeout
            );
        }

        if (config('services.gemini.api_key')) {
            return $this->generateViaGeminiApi($prompt);
        }

        throw new RuntimeException(
            'Google AI configuration is incomplete. Set Vertex AI env values or GEMINI_API_KEY.'
        );
    }

    protected function generateViaVertex(
        string $prompt,
        string $project,
        string $location,
        string $model,
        int $timeout
    ): string {
        $token = $this->getAccessToken();

        $url = sprintf(
            'https://aiplatform.googleapis.com/v1/projects/%s/locations/%s/publishers/google/models/%s:generateContent',
            $project,
            $location,
            $model
        );

        $response = Http::timeout($timeout)
            ->withToken($token)
            ->acceptJson()
            ->post($url, [
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
            ]);

        if (!$response->successful()) {
            throw new RuntimeException(
                'Vertex AI request failed. HTTP ' .
                $response->status() .
                ': ' .
                $response->body()
            );
        }

        $data = $response->json();

        $text = data_get(
            $data,
            'candidates.0.content.parts.0.text'
        );

        if (!is_string($text) || trim($text) === '') {
            throw new RuntimeException(
                'Vertex AI returned an empty response.'
            );
        }

        return trim($text);
    }

    protected function generateViaGeminiApi(string $prompt): string
    {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model');
        $baseUrl = rtrim((string) config('services.gemini.base_url'), '/');
        $timeout = (int) config('services.gemini.timeout', 120);

        if (!$apiKey || !$model || !$baseUrl) {
            throw new RuntimeException('Gemini API configuration is incomplete.');
        }

        $response = Http::timeout($timeout)
            ->acceptJson()
            ->post(
                sprintf('%s/models/%s:generateContent?key=%s', $baseUrl, $model, $apiKey),
                [
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
                ]
            );

        if (!$response->successful()) {
            throw new RuntimeException(
                'Gemini API request failed. HTTP ' .
                $response->status() .
                ': ' .
                $response->body()
            );
        }

        $text = data_get(
            $response->json(),
            'candidates.0.content.parts.0.text'
        );

        if (!is_string($text) || trim($text) === '') {
            throw new RuntimeException('Gemini API returned an empty response.');
        }

        return trim($text);
    }

    protected function getAccessToken(): string
    {
        $response = Http::timeout(10)
            ->withHeaders([
                'Metadata-Flavor' => 'Google',
            ])
            ->get(
                'http://metadata.google.internal/computeMetadata/v1/instance/service-accounts/default/token'
            );

        if (!$response->successful()) {
            throw new RuntimeException(
                'Unable to obtain Google Cloud access token. HTTP ' .
                $response->status()
            );
        }

        $token = $response->json('access_token');

        if (!is_string($token) || trim($token) === '') {
            throw new RuntimeException(
                'Google Cloud access token was not returned.'
            );
        }

        return $token;
    }
}
