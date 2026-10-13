<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Aldef Store ZIP import. Its working files live under
 * storage/app/store-imports/{id} (never public) until the import ends.
 */
class StoreImport extends Model
{
    public const UPLOADING = 'uploading';
    public const EXTRACTING = 'extracting';
    public const READY = 'ready';
    public const IMPORTING = 'importing';
    public const DONE = 'done';
    public const FAILED = 'failed';
    public const CANCELLED = 'cancelled';
    public const EXPIRED = 'expired';

    /** States whose working files still exist on disk. */
    public const ACTIVE = [self::UPLOADING, self::EXTRACTING, self::READY, self::IMPORTING];

    protected $fillable = ['user_id', 'original_name', 'size', 'received', 'status', 'cursor', 'total', 'plan', 'summary', 'error'];

    protected $casts = [
        'plan' => 'array',
        'summary' => 'array',
        'size' => 'integer',
        'received' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workDir(): string
    {
        return storage_path('app/store-imports/' . $this->id);
    }

    public function zipPath(): string
    {
        return $this->workDir() . '/upload.zip';
    }

    public function filesDir(): string
    {
        return $this->workDir() . '/files';
    }

    public function isActive(): bool
    {
        return in_array($this->status, self::ACTIVE, true);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::UPLOADING => 'Mengunggah',
            self::EXTRACTING => 'Mengekstrak',
            self::READY => 'Siap diimpor',
            self::IMPORTING => 'Mengimpor',
            self::DONE => 'Selesai',
            self::FAILED => 'Gagal',
            self::CANCELLED => 'Dibatalkan',
            self::EXPIRED => 'Kedaluwarsa',
            default => $this->status,
        };
    }

    /** Plan entries counted by state: new / skip / error, and results once run. */
    public function counts(): array
    {
        $plan = collect($this->plan ?? []);

        return [
            'products' => $plan->count(),
            'new' => $plan->where('state', 'new')->count(),
            'skip' => $plan->where('state', 'skip')->count(),
            'error' => $plan->where('state', 'error')->count(),
            'created' => $plan->where('result', 'created')->count(),
            'failed' => $plan->where('result', 'failed')->count(),
            'images' => $plan->sum(fn ($p) => count($p['images'] ?? [])),
            'videos' => $plan->sum(fn ($p) => count($p['videos'] ?? [])),
        ];
    }
}
