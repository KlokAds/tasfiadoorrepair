<?php

namespace Tests\Feature;

use App\Models\PageSeo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PageSeoCacheTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_seo_still_applies_when_read_from_the_cache(): void
    {
        PageSeo::updateOrCreate(['key' => 'contact'], ['meta_title' => 'Talk to us', 'meta_desc' => 'Reach our door repair team by phone or WhatsApp.']);
        Cache::forget('page_seo.all');

        $this->get('/contact')->assertOk(); // fills the cache
        $html = $this->get('/contact')->assertOk()->getContent(); // read back from the cache

        $this->assertStringContainsString('Reach our door repair team by phone or WhatsApp.', $html);
        $this->assertSame('Talk to us', PageSeo::forKey('contact')?->meta_title);
    }
}
