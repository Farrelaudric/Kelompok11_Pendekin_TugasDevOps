<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortLinkRequest;
use App\Models\ShortLink;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShortLinkController extends Controller
{
    /**
     * Display the short link creation form, recent result, and links list with click counts.
     */
    public function index(): View
    {
        $shortLinks = ShortLink::withCount('clicks')
            ->latest()
            ->get();

        return view('short-links.index', compact('shortLinks'));
    }

    /**
     * Store a newly created short link with a custom alias or random 6-character code.
     */
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

    /**
     * Deactivate an existing short link.
     */
    public function deactivate(int $id): RedirectResponse
    {
        $shortLink = ShortLink::findOrFail($id);

        $shortLink->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('short-links.index')
            ->with('success', 'Short link berhasil dinonaktifkan.');
    }

    /**
     * Delete an existing short link and its click statistics.
     */
    public function destroy(int $id): RedirectResponse
    {
        $shortLink = ShortLink::findOrFail($id);

        // Explicitly remove associated clicks before deleting the link
        $shortLink->clicks()->delete();

        // Delete the short link record
        $shortLink->delete();

        return redirect()
            ->route('short-links.index')
            ->with('success', 'Short link berhasil dihapus.');
    }

    /**
     * Redirect to the original URL using the short code and record click.
     */
    public function redirect(string $code): RedirectResponse
    {
        $shortLink = ShortLink::where('code', $code)->first();

        // 1 & 2. Kode tidak ditemukan
        if (! $shortLink) {
            abort(404);
        }

        // 3. Link dinonaktifkan
        if (! $shortLink->is_active) {
            abort(410, 'Link ini sudah tidak aktif.');
        }

        // 4. Link sudah kedaluwarsa
        if ($shortLink->isExpired()) {
            abort(410, 'Link ini sudah kedaluwarsa.');
        }

        // 5. Catat satu klik
        $shortLink->clicks()->create([
            'clicked_at' => now(),
        ]);

        // 6. Redirect ke URL asli
        return redirect()->away($shortLink->original_url);
    }
}
