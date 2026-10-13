<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\StoreBrand;
use App\Models\StoreCategory;
use App\Models\StoreImport;
use App\Models\StoreProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

/**
 * Aldef Store brands and the bulk ZIP import
 * (Brand/Kategori/Subkategori/Nama Produk/ with photos and videos).
 */
class StoreBrandAndImportTest extends TestCase
{
    use RefreshDatabase;

    private const PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
    private const MP4 = "\x00\x00\x00\x18ftypmp42\x00\x00\x00\x00mp42isom\x00\x00\x00\x08free";

    private User $admin;
    private array $zips = [];

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $role = Role::create(['name' => 'content', 'display_name' => 'Content']);
        $role->permissions()->attach(Permission::create(['name' => 'manage-content', 'display_name' => 'Manage Content']));
        $this->admin = User::factory()->create();
        $this->admin->roles()->attach($role);
    }

    protected function tearDown(): void
    {
        foreach (StoreImport::all() as $import) {
            File::deleteDirectory($import->workDir());
        }
        foreach ($this->zips as $zip) {
            @unlink($zip);
        }
        parent::tearDown();
    }

    /** @param array<string, string> $files path => contents */
    private function makeZip(array $files): string
    {
        $path = tempnam(sys_get_temp_dir(), 'store') . '.zip';
        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        foreach ($files as $name => $contents) {
            $zip->addFromString($name, $contents);
        }
        $zip->close();

        return $this->zips[] = $path;
    }

    /** Upload in small chunks (one sent twice) and extract to the preview. */
    private function uploadAndExtract(string $zipPath): StoreImport
    {
        $bytes = file_get_contents($zipPath);

        $start = $this->actingAs($this->admin)
            ->postJson('/admin/store/imports', ['name' => 'katalog.zip', 'size' => strlen($bytes)])
            ->assertOk()->json();

        $chunks = str_split($bytes, (int) ceil(strlen($bytes) / 3));
        $offset = 0;
        foreach ($chunks as $i => $chunk) {
            $send = fn () => $this->actingAs($this->admin)->post($start['chunk_url'], [
                'offset' => $offset,
                'chunk' => UploadedFile::fake()->createWithContent('chunk.bin', $chunk),
            ], ['Accept' => 'application/json']);
            $send()->assertOk();
            if ($i === 0) {
                // A retry of a chunk the server already has changes nothing.
                $send()->assertOk()->assertJson(['received' => strlen($chunk)]);
            }
            $offset += strlen($chunk);
        }

        for ($i = 0; $i < 20; $i++) {
            $status = $this->actingAs($this->admin)->postJson($start['extract_url'])->assertOk()->json('status');
            if ($status !== 'extracting') {
                break;
            }
        }

        return StoreImport::findOrFail($start['id']);
    }

    private function runImport(StoreImport $import): StoreImport
    {
        for ($i = 0; $i < 20; $i++) {
            if ($this->actingAs($this->admin)->postJson(route('admin.store.imports.run', $import))->assertOk()->json('status') === 'done') {
                break;
            }
        }

        return $import->fresh();
    }

    public function test_zip_import_creates_drafts_in_brand_category_and_subcategory(): void
    {
        $png = base64_decode(self::PNG);
        $nike = StoreBrand::create(['name' => 'Nike', 'slug' => 'nike']);
        StoreProduct::createWithSku(['name' => 'Air Force 1', 'slug' => 'air-force-1', 'status' => 'published', 'store_brand_id' => $nike->id]);

        $zip = $this->makeZip([
            'Katalog/Nike/Sepatu Lari/Trail/Pegasus Trail 5/2.jpg' => $png,
            'Katalog/Nike/Sepatu Lari/Trail/Pegasus Trail 5/10.jpg' => $png,
            'Katalog/Nike/Sepatu Lari/Trail/Pegasus Trail 5/1.jpg' => $png,
            'Katalog/Nike/Sepatu Lari/Trail/Pegasus Trail 5/video.mp4' => self::MP4,
            'Katalog/Nike/Sepatu Lari/Trail/Pegasus Trail 5/rusak.mp4' => 'not a video',
            'Katalog/nike/sneakers/Air Force 1/1.png' => $png,
            'Katalog/Adidas/Samba OG/1.webp' => $png,
            'Katalog/Adidas/Samba OG/.DS_Store' => 'junk',
            'Katalog/Adidas/katalog.pdf' => '%PDF-1.4',
            'Katalog/__MACOSX/Adidas/._1.webp' => 'junk',
            'Katalog/Puma/1.jpg' => $png,
            'Katalog/Nike/A/B/C/Terlalu Dalam/1.jpg' => $png,
            '../evil.jpg' => $png,
        ]);

        $import = $this->uploadAndExtract($zip);
        $this->assertSame('ready', $import->status);
        $this->assertSame('Katalog', $import->summary['wrapper']);
        $this->assertFileDoesNotExist($import->zipPath());
        $this->assertFileDoesNotExist(storage_path('app/store-imports/evil.jpg'));

        // Preview: nothing has been created yet.
        $this->assertSame(1, StoreProduct::count());
        $plan = collect($import->plan)->keyBy('name');
        $this->assertSame('new', $plan['Pegasus Trail 5']['state']);
        $this->assertSame(['1.jpg', '2.jpg', '10.jpg'], array_values($plan['Pegasus Trail 5']['images']));
        $this->assertSame(['video.mp4'], array_values($plan['Pegasus Trail 5']['videos']));
        $this->assertSame('skip', $plan['Air Force 1']['state']);
        $this->assertSame('new', $plan['Samba OG']['state']);
        $this->assertSame('error', $plan['Puma']['state']);
        $this->assertSame('error', $plan['Terlalu Dalam']['state']);
        $notes = implode("\n", $import->summary['notes']);
        $this->assertStringContainsString('katalog.pdf', $notes);
        $this->assertStringContainsString('rusak.mp4', $notes);
        $this->assertStringContainsString('jalur tidak aman', $notes);

        $this->actingAs($this->admin)->get(route('admin.store.imports.show', $import))->assertOk()
            ->assertSee('Pratinjau siap')->assertSee('Pegasus Trail 5')->assertSee('Mulai Import');

        $import = $this->runImport($import);
        $this->assertSame('done', $import->status);
        $this->assertDirectoryDoesNotExist($import->workDir());

        $pegasus = StoreProduct::with(['brand', 'category.parent', 'images'])->where('name', 'Pegasus Trail 5')->sole();
        $this->assertSame('draft', $pegasus->status);
        $this->assertMatchesRegularExpression('/^ATS\d{13}$/', $pegasus->sku);
        $this->assertSame($nike->id, $pegasus->store_brand_id);
        $this->assertSame('Sepatu Lari › Trail', $pegasus->category->label());
        $this->assertSame(['image', 'image', 'image', 'video'], $pegasus->images->pluck('type')->all());
        $this->assertTrue($pegasus->images->first()->is_primary);
        foreach ($pegasus->images as $item) {
            Storage::disk('public')->assertExists($item->path);
            $this->assertStringStartsWith('media/', $item->path);
        }

        $samba = StoreProduct::with(['brand', 'category'])->where('name', 'Samba OG')->sole();
        $this->assertSame('Adidas', $samba->brand->name);
        $this->assertNull($samba->category);

        // The existing product was left alone; nothing was duplicated.
        $this->assertSame(1, StoreProduct::where('name', 'Air Force 1')->count());
        $this->assertSame(3, StoreProduct::count());
        $this->assertSame(1, StoreBrand::whereRaw('LOWER(name) = ?', ['nike'])->count());

        $this->actingAs($this->admin)->get(route('admin.store.imports.show', $import))->assertOk()
            ->assertSee('Import selesai')->assertSee($pegasus->sku);
    }

    public function test_a_single_top_folder_can_be_a_brand_or_a_wrapper(): void
    {
        $import = $this->uploadAndExtract($this->makeZip([
            'Nike/Lari/Pegasus/1.jpg' => base64_decode(self::PNG),
        ]));

        // Ambiguous: kept as the brand by default.
        $this->assertNull($import->summary['wrapper']);
        $this->assertSame('Nike', $import->plan[0]['brand']);
        $this->assertSame('Lari', $import->plan[0]['category']);

        $this->actingAs($this->admin)->post(route('admin.store.imports.plan', $import), ['strip_wrapper' => 1])->assertRedirect();
        $import->refresh();
        $this->assertSame('Nike', $import->summary['wrapper']);
        $this->assertSame('Lari', $import->plan[0]['brand']);
        $this->assertNull($import->plan[0]['category']);
    }

    public function test_upload_rules_and_cancel(): void
    {
        $this->actingAs($this->admin)->postJson('/admin/store/imports', ['name' => 'produk.rar', 'size' => 100])->assertStatus(422);
        $this->actingAs($this->admin)->postJson('/admin/store/imports', ['name' => 'besar.zip', 'size' => 501 * 1024 * 1024])->assertStatus(422);

        $id = $this->actingAs($this->admin)->postJson('/admin/store/imports', ['name' => 'a.zip', 'size' => 10])->json('id');
        $import = StoreImport::find($id);

        // A chunk at the wrong offset is refused and the server says where it is.
        $this->actingAs($this->admin)->post(route('admin.store.imports.chunk', $import), [
            'offset' => 5, 'chunk' => UploadedFile::fake()->createWithContent('c.bin', 'abc'),
        ], ['Accept' => 'application/json'])->assertStatus(409)->assertJson(['received' => 0]);

        $this->actingAs($this->admin)->delete(route('admin.store.imports.destroy', $import))->assertRedirect();
        $this->assertSame('cancelled', $import->fresh()->status);
        $this->assertDirectoryDoesNotExist($import->workDir());

        // A ZIP that is not a ZIP fails cleanly.
        $bad = $this->actingAs($this->admin)->postJson('/admin/store/imports', ['name' => 'b.zip', 'size' => 9])->json();
        $this->actingAs($this->admin)->post($bad['chunk_url'], [
            'offset' => 0, 'chunk' => UploadedFile::fake()->createWithContent('c.bin', 'not a zip'),
        ], ['Accept' => 'application/json'])->assertOk();
        $this->actingAs($this->admin)->postJson($bad['extract_url'])->assertOk()->assertJson(['status' => 'failed']);
    }

    public function test_import_requires_content_permission(): void
    {
        $this->post('/admin/store/imports', ['name' => 'a.zip', 'size' => 10])->assertRedirect(route('admin.login'));

        $role = Role::create(['name' => 'sales', 'display_name' => 'Sales']);
        $role->permissions()->attach(Permission::create(['name' => 'manage-leads', 'display_name' => 'Leads']));
        $sales = User::factory()->create();
        $sales->roles()->attach($role);

        $this->actingAs($sales)->get('/admin/store/imports')->assertForbidden();
        $this->actingAs($sales)->postJson('/admin/store/imports', ['name' => 'a.zip', 'size' => 10])->assertForbidden();
        $this->actingAs($sales)->get('/admin/store/brands')->assertForbidden();
    }

    public function test_brands_crud_filters_and_bulk_publish(): void
    {
        $this->actingAs($this->admin)->post('/admin/store/brands', ['name' => 'Nike'])->assertRedirect();
        $this->actingAs($this->admin)->post('/admin/store/brands', ['name' => 'nike'])->assertSessionHasErrors('name');
        $nike = StoreBrand::sole();
        $this->assertSame('nike', $nike->slug);

        $running = StoreCategory::create(['name' => 'Sepatu Lari', 'slug' => 'sepatu-lari']);
        $a = StoreProduct::createWithSku(['name' => 'Pegasus', 'slug' => 'pegasus', 'status' => 'draft', 'store_brand_id' => $nike->id, 'store_category_id' => $running->id]);
        $b = StoreProduct::createWithSku(['name' => 'Samba', 'slug' => 'samba', 'status' => 'draft']);

        $this->actingAs($this->admin)->get('/admin/store/brands')->assertOk()
            ->assertSee('Nike')->assertSee('Sepatu Lari')->assertSee('1 produk belum punya brand');
        $this->actingAs($this->admin)->get('/admin/store/products?brand=' . $nike->id)->assertOk()
            ->assertSee('Pegasus')->assertDontSee('Samba');

        $this->actingAs($this->admin)->post(route('admin.store.products.bulk'), ['action' => 'publish', 'ids' => [$a->id, $b->id]])->assertRedirect();
        $this->assertSame(['published', 'published'], StoreProduct::orderBy('id')->pluck('status')->all());
        $this->assertNotNull($a->fresh()->published_at);

        $this->get('/store?brand=nike')->assertOk()->assertSee('Pegasus')->assertDontSee('Samba');
        $this->get('/store?q=nike')->assertOk()->assertSee('Pegasus');
        $this->get('/store/pegasus')->assertOk()->assertSee('/store?brand=nike', false);

        $this->actingAs($this->admin)->delete(route('admin.store.brands.destroy', $nike));
        $this->assertNull($a->fresh()->store_brand_id);
    }

    public function test_sidebar_lists_the_shoe_submenus(): void
    {
        $this->actingAs($this->admin)->get('/admin/store/imports')->assertOk()
            ->assertSeeInOrder(['Sepatu', 'Produk Sepatu', 'Brand Sepatu', 'Kategori Sepatu', 'Import ZIP'])
            ->assertSee('Unggah ZIP');
    }

    public function test_existing_brand_text_is_carried_over_by_the_migration(): void
    {
        // Back to the schema before brands existed, with a typed brand.
        Artisan::call('migrate:rollback', ['--step' => 2]);
        DB::table('store_products')->insert([
            'name' => 'Lama', 'slug' => 'lama', 'sku' => 'ATS0101202600001', 'sku_sequence' => 1,
            'brand' => '  Nike ', 'status' => 'draft', 'size_system' => 'EU', 'sort_order' => 0,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        Artisan::call('migrate');

        $product = StoreProduct::with('brand')->where('slug', 'lama')->sole();
        $this->assertSame('Nike', $product->brand->name);
        $this->assertFalse(\Illuminate\Support\Facades\Schema::hasColumn('store_products', 'brand'));
    }
}
