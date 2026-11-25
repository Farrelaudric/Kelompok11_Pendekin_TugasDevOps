<?php

namespace Tests\Feature;

use App\Models\ShortLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_short_link_with_valid_url_and_custom_alias(): void
    {
        $response = $this->post('/short-links', [
            'original_url' => 'https://toko-umkm.id/katalog',
            'custom_alias' => 'promo-lebaran',
        ]);

        $response->assertRedirect(route('short-links.index'));
        $this->assertDatabaseHas('short_links', [
            'code' => 'promo-lebaran',
            'original_url' => 'https://toko-umkm.id/katalog',
            'is_active' => true,
        ]);
    }

    public function test_rejects_invalid_url_and_invalid_custom_alias(): void
    {
        $response = $this->post('/short-links', [
            'original_url' => 'ftp://bukan-http.com',
            'custom_alias' => 'spasi dan@simbol',
        ]);

        $response->assertSessionHasErrors(['original_url', 'custom_alias']);
    }

    public function test_active_short_link_redirects_with_302(): void
    {
        ShortLink::create([
            'code' => 'umkm01',
            'original_url' => 'https://example.com/katalog',
            'is_active' => true,
        ]);

        $response = $this->get('/umkm01');
        $response->assertStatus(302);
        $response->assertRedirect('https://example.com/katalog');
    }

    public function test_deactivated_or_expired_link_returns_410(): void
    {
        $inactive = ShortLink::create([
            'code' => 'nonaktif1',
            'original_url' => 'https://example.com/a',
            'is_active' => false,
        ]);

        $expired = ShortLink::create([
            'code' => 'expired1',
            'original_url' => 'https://example.com/b',
            'is_active' => true,
            'expires_at' => now()->subDay(),
        ]);

        $this->get('/' . $inactive->code)->assertStatus(410);
        $this->get('/' . $expired->code)->assertStatus(410);
    }
}