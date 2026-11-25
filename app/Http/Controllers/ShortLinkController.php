<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortLinkRequest;
use App\Models\ShortLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ShortLinkController extends Controller
{
    public function index(): View
    {
        $shortLinks = ShortLink::withCount('clicks')
            ->latest()
            ->get();

        return view('short-links.index', compact('shortLinks'));
    }

    public function store(StoreShortLinkRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $code = ! empty($validated['custom_alias'])
            ? $validated['custom_alias']
            : ShortLink::generateUniqueCode(6);

        $shortLink = ShortLink::create([
            'code' => $code,
            'original_url' => $validated['original_url'],
            'is_active' => true,
            'expires_at' => ! empty($validated['expires_at']) ? $validated['expires_at'] : null,
        ]);

        return redirect()
            ->route('short-links.index')
            ->with('success', 'Short link berhasil dibuat!')
            ->with('created_link', [
                'id' => $shortLink->id,
                'code' => $shortLink->code,
                'original_url' => $shortLink->original_url,
                'is_active' => (bool) $shortLink->is_active,
                'expires_at' => $shortLink->expires_at?->format('Y-m-d H:i'),
            ]);
    }

    public function deactivate(int $id): RedirectResponse
    {
        $shortLink = ShortLink::findOrFail($id);

        $shortLink->update([
            'is_active' => false,
        ]);

        Cache::forget("shortlink:{$shortLink->code}");

        return redirect()
            ->route('short-links.index')
            ->with('success', 'Short link berhasil dinonaktifkan.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $shortLink = ShortLink::findOrFail($id);

        Cache::forget("shortlink:{$shortLink->code}");
        $shortLink->clicks()->delete();
        $shortLink->delete();

        return redirect()
            ->route('short-links.index')
            ->with('success', 'Short link berhasil dihapus.');
    }

    public function redirect(string $code): RedirectResponse
    {
        $cacheKey = "shortlink:{$code}";

        $shortLink = Cache::remember($cacheKey, now()->addHour(), function () use ($code) {
            return ShortLink::where('code', $code)->first();
        });

        if (! $shortLink) {
            Cache::forget($cacheKey);
            abort(404);
        }

        if (! $shortLink->is_active) {
            Cache::forget($cacheKey);
            abort(410, 'Link ini sudah tidak aktif.');
        }

        if ($shortLink->isExpired()) {
            Cache::forget($cacheKey);
            abort(410, 'Link ini sudah kedaluwarsa.');
        }

        $shortLink->clicks()->create([
            'clicked_at' => now(),
        ]);

        return redirect()->away($shortLink->original_url);
    }
}