<?php

namespace App\Services;

use App\Models\BacklinkProspect;
use App\Models\SeoOutreachDraft;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SeoOutreachService
{
    public function draft(BacklinkProspect $prospect): SeoOutreachDraft
    {
        if ($prospect->status !== 'approved' || ! $prospect->post?->isPublished()) {
            throw new RuntimeException('Prospek harus disetujui dan artikel tujuan harus terbit.');
        }
        if ($existing = $prospect->outreach) {
            return $existing;
        }
        $post = $prospect->post;
        $destination = route('blog.show', $post->slug);
        $data = app(SeoResearchService::class)->json('outreach:' . $prospect->id,
            'Buat draf outreach Bahasa Indonesia yang ringkas dan personal hanya dari bukti. Jangan menebak nama penerima, email, relasi atau janji penempatan. '
            . 'Subjek, perkenalan, alasan relevansi artikel, resource dan CTA natural; tanpa permintaan tautan berbayar/pertukaran. '
            . 'Jika kategori guest_post, sarankan guest_post {title, outline (array string), audience}; selain itu guest_post null. '
            . 'JSON {"subject":"...","body":"...","guest_post":null}. Teks biasa, tidak perlu URL dalam body karena resource disertakan aplikasi.',
            ['website' => $prospect->domain, 'category' => $prospect->category,
                'evidence' => $prospect->evidence['source'] ?? [], 'article' => $post->getBase('title'),
                'summary' => app(SeoResearchService::class)->text($post->getBase('excerpt'), 500)], [
                'subject' => 'required|string|max:200', 'body' => 'required|string|max:5000',
                'guest_post' => 'nullable|array:title,outline,audience',
                'guest_post.title' => 'required_with:guest_post|string|max:255',
                'guest_post.outline' => 'required_with:guest_post|array|max:8',
                'guest_post.outline.*' => 'required|string|max:500',
                'guest_post.audience' => 'required_with:guest_post|string|max:500',
            ]);
        return DB::transaction(function () use ($prospect, $post, $destination, $data) {
            $locked = BacklinkProspect::lockForUpdate()->findOrFail($prospect->id);
            if ($locked->status !== 'approved' || ! $locked->post?->isPublished()) {
                throw new RuntimeException('Persetujuan prospek berubah.');
            }
            $guest = $locked->category === 'guest_post' ? ($data['guest_post'] ?? null) : null;
            if ($guest) {
                $guest['destination'] = $destination;
                $guest['anchor'] = $post->getBase('title');
            }
            return SeoOutreachDraft::firstOrCreate(['backlink_prospect_id' => $locked->id], [
                'subject' => $data['subject'],
                'body' => $data['body'] . "\n\nResource: " . $destination,
                'guest_post' => $guest, 'status' => 'draft',
            ]);
        });
    }
}
