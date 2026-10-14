<?php

namespace Tests\Feature;

use App\Models\StoreBrand;
use App\Models\StoreProduct;
use App\Models\StoreProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The WhatsApp enquiry carries the photo the visitor was looking at as a link
 * (?foto=N); that page offers the photo as a JPEG og:image, so the chat shows
 * it as the link preview.
 */
class StoreWhatsAppPreviewTest extends TestCase
{
    use RefreshDatabase;

    private StoreProduct $product;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        File::deleteDirectory(storage_path('app/store-previews'));

        $this->product = StoreProduct::createWithSku([
            'name' => 'Gel Kayano', 'slug' => 'gel-kayano', 'status' => 'published', 'published_at' => now(),
            'store_brand_id' => StoreBrand::findOrCreateByName('Asics')->id,
        ]);

        // Photo 1 (main), a video, then photo 3 as WebP, as the uploader keeps them.
        $files = [
            ['media/satu.png', 'image', fn ($path) => imagepng($this->canvas(1600, 1200), $path)],
            ['media/klip.mp4', 'video', fn ($path) => file_put_contents($path, "\x00\x00\x00\x18ftypmp42")],
            ['media/tiga.webp', 'image', fn ($path) => imagewebp($this->canvas(900, 1200), $path)],
        ];
        foreach ($files as $i => [$path, $type, $write]) {
            $full = Storage::disk('public')->path($path);
            File::ensureDirectoryExists(dirname($full));
            $write($full);
            StoreProductImage::create([
                'store_product_id' => $this->product->id, 'path' => $path, 'type' => $type,
                'mime' => $type === 'video' ? 'video/mp4' : null, 'is_primary' => $i === 0, 'sort_order' => $i,
            ]);
        }
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(storage_path('app/store-previews'));
        parent::tearDown();
    }

    /** The picker configuration: @js() emits JSON.parse('…'), decoded twice. */
    private function config(string $html): array
    {
        $this->assertSame(1, preg_match("/x-data=\"storeProduct\\(JSON\\.parse\\('(.*?)'\\)\\)\"/s", $html, $m));

        return json_decode(json_decode('"' . $m[1] . '"'), true);
    }

    private function canvas(int $width, int $height): \GdImage
    {
        $image = imagecreatetruecolor($width, $height);
        imagefill($image, 0, 0, imagecolorallocate($image, 30, 90, 200));

        return $image;
    }

    public function test_a_product_photo_is_served_as_a_jpeg_preview(): void
    {
        if (! function_exists('imagewebp')) {
            $this->markTestSkipped('GD without WebP');
        }

        $response = $this->get('/store/gel-kayano/foto/3.jpg')->assertOk();
        $this->assertSame('image/jpeg', $response->headers->get('Content-Type'));
        $file = $response->baseResponse->getFile()->getPathname();
        $this->assertSame([900, 1200, IMAGETYPE_JPEG], array_slice(getimagesize($file), 0, 3));

        // Large photos are scaled to 1200 px at most.
        $file = $this->get('/store/gel-kayano/foto/1.jpg')->assertOk()->baseResponse->getFile()->getPathname();
        $this->assertSame([1200, 900], array_slice(getimagesize($file), 0, 2));

        // A video, a number past the end, or a draft has no preview.
        $this->get('/store/gel-kayano/foto/2.jpg')->assertNotFound();
        $this->get('/store/gel-kayano/foto/9.jpg')->assertNotFound();
        $this->product->update(['status' => 'draft']);
        $this->get('/store/gel-kayano/foto/1.jpg')->assertNotFound();
    }

    public function test_a_shared_photo_link_previews_that_photo_and_keeps_the_canonical(): void
    {
        $productUrl = route('store.show', 'gel-kayano');

        $this->get('/store/gel-kayano')->assertOk()
            ->assertSee('<meta property="og:image" content="' . route('store.photo', ['gel-kayano', 1]) . '"', false)
            ->assertSee('<meta property="og:url" content="' . $productUrl . '"', false);

        $html = $this->get('/store/gel-kayano?foto=3')->assertOk()
            ->assertSee('<meta property="og:image" content="' . route('store.photo', ['gel-kayano', 3]) . '"', false)
            ->assertSee('<meta property="og:url" content="' . $productUrl . '?foto=3"', false)
            ->assertSee('<link rel="canonical" href="' . $productUrl . '">', false)
            ->getContent();
        // The gallery opens on that photo.
        $this->assertSame(2, $this->config($html)['start']);

        // On a video the preview falls back to the main photo; nonsense is ignored.
        $this->get('/store/gel-kayano?foto=2')->assertOk()
            ->assertSee('<meta property="og:image" content="' . route('store.photo', ['gel-kayano', 1]) . '"', false);
        $this->get('/store/gel-kayano?foto=99')->assertOk()
            ->assertSee('<meta property="og:url" content="' . $productUrl . '"', false);
    }

    public function test_page_hands_the_message_its_details(): void
    {
        $html = $this->get('/store/gel-kayano')->assertOk()
            // No size options set: the visitor is asked to type one.
            ->assertSee('id="store-size"', false)
            ->assertSee('Ukuran yang Anda inginkan')
            ->getContent();

        $config = $this->config($html);
        $this->assertSame('Asics', $config['brand']);
        $this->assertSame(['image', 'video', 'image'], $config['media']);
        $this->assertTrue($config['askSize']);
        $this->assertSame('Tulis ukuran yang Anda inginkan.', $config['messages']['type-size']);
    }
}
