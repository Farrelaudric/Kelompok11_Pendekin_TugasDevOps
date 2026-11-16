<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShortLink extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'short_links';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'code',
        'original_url',
        'expires_at',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the clicks recorded for this short link.
     */
    public function clicks(): HasMany
    {
        return $this->hasMany(LinkClick::class, 'short_link_id');
    }

    /**
     * Get clicks aggregated per day using database grouping.
     */
    public function getDailyStats(): Collection
    {
        return $this->clicks()
            ->selectRaw('DATE(clicked_at) as click_date, COUNT(*) as total_clicks')
            ->groupBy('click_date')
            ->orderByDesc('click_date')
            ->get();
    }

    /**
     * Determine if the short link has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Get status label: Aktif, Nonaktif, or Expired.
     */
    public function getStatusLabelAttribute(): string
    {
        if (! $this->is_active) {
            return 'Nonaktif';
        }

        if ($this->isExpired()) {
            return 'Expired';
        }

        return 'Aktif';
    }

    /**
     * Get human-readable formatted expiration time.
     */
    public function getFormattedExpiresAtAttribute(): string
    {
        if (! $this->expires_at) {
            return 'Tidak ada';
        }

        return $this->expires_at->locale('id')->translatedFormat('d F Y, H:i');
    }

    /**
     * Determine if link can be deactivated.
     */
    public function canBeDeactivated(): bool
    {
        return $this->is_active && ! $this->isExpired();
    }

    /**
     * Generate a unique 6-character alphanumeric random code.
     * Automatically handles collision by generating a new code if already used.
     */
    public static function generateUniqueCode(int $length = 6): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charLength = strlen($characters);
        $maxAttempts = 10;
        $attempt = 0;

        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[random_int(0, $charLength - 1)];
            }
            $attempt++;

            if ($attempt > $maxAttempts) {
                throw new \RuntimeException('Gagal menghasilkan kode unik. Silakan coba lagi.');
            }
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
