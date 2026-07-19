<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\Reference;
use App\Models\ReferenceImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminContentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Nahrané soubory míří přímo do public/, proto se po každém testu
     * uklízí ručně -- Storage::fake() sem nedosáhne.
     *
     * @var array<int, string>
     */
    protected array $created = [];

    protected function tearDown(): void
    {
        foreach ($this->created as $path) {
            if (is_file($path)) {
                unlink($path);
            }
        }

        parent::tearDown();
    }

    protected function admin(): User
    {
        return User::factory()->create();
    }

    protected function track(string $relativePath): string
    {
        $this->created[] = public_path($relativePath);

        return $relativePath;
    }

    public function test_admin_pages_require_authentication(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/admin/reference')->assertRedirect('/login');
        $this->get('/admin/certificate')->assertRedirect('/login');
        $this->post('/admin/publish')->assertRedirect('/login');
    }

    public function test_dashboard_and_listings_render(): void
    {
        $this->actingAs($this->admin());

        $this->get('/admin')->assertOk()->assertSee('Zákazníci');
        $this->get('/admin/reference')->assertOk()->assertSee('Reference');
        $this->get('/admin/certificate')->assertOk()->assertSee('Certifikáty');
        $this->get('/admin/reference/create')->assertOk();
        $this->get('/admin/certificate/create')->assertOk();
    }

    public function test_certificate_can_be_created_with_an_image_in_public(): void
    {
        $this->actingAs($this->admin());

        $response = $this->post('/admin/certificate', [
            'title' => 'Testovací certifikát',
            'image' => UploadedFile::fake()->image('bramac test.jpg', 200, 300),
            'sort_order' => 3,
        ]);

        $response->assertRedirect(route('certificate.index'));

        $certificate = Certificate::firstWhere('title', 'Testovací certifikát');
        $this->assertNotNull($certificate);
        $this->track($certificate->image);

        // Uloženo do public/, s bezpečným názvem bez mezer
        $this->assertStringStartsWith('images/certificates/', $certificate->image);
        $this->assertStringNotContainsString(' ', $certificate->image);
        $this->assertFileExists(public_path($certificate->image));
    }

    public function test_certificate_validation_rejects_missing_data(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/certificate', [])
            ->assertSessionHasErrors(['title', 'image']);

        $this->post('/admin/certificate', [
            'title' => 'Bez obrázku',
            'image' => UploadedFile::fake()->create('smlouva.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('image');

        $this->assertSame(0, Certificate::count());
    }

    public function test_deleting_a_certificate_removes_its_file(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/certificate', [
            'title' => 'Ke smazání',
            'image' => UploadedFile::fake()->image('smazat.jpg'),
        ]);

        $certificate = Certificate::firstWhere('title', 'Ke smazání');
        $path = public_path($certificate->image);
        $this->assertFileExists($path);

        $this->delete(route('certificate.destroy', $certificate))
            ->assertRedirect(route('certificate.index'));

        $this->assertFileDoesNotExist($path);
        $this->assertSame(0, Certificate::count());
    }

    public function test_reference_can_be_created_with_a_gallery(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/reference', [
            'title' => 'Střecha',
            'place' => 'Testov',
            'category' => 'strechy',
            'images' => [
                UploadedFile::fake()->image('a.jpg'),
                UploadedFile::fake()->image('b.jpg'),
            ],
        ])->assertRedirect(route('reference.index'));

        $reference = Reference::firstWhere('title', 'Střecha');
        $this->assertNotNull($reference);
        $this->assertSame(2, $reference->images()->count());

        foreach ($reference->images as $image) {
            $this->track($image->path);
            $this->assertStringStartsWith('images/reference/strechy/', $image->path);
            $this->assertFileExists(public_path($image->path));
        }
    }

    public function test_reference_rejects_an_unknown_category(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/reference', [
            'title' => 'Neznámá',
            'category' => 'bazeny',
            'images' => [UploadedFile::fake()->image('a.jpg')],
        ])->assertSessionHasErrors('category');

        $this->assertSame(0, Reference::count());
    }

    public function test_a_single_gallery_image_can_be_deleted(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/reference', [
            'title' => 'Galerie',
            'category' => 'pergoly',
            'images' => [UploadedFile::fake()->image('a.jpg'), UploadedFile::fake()->image('b.jpg')],
        ]);

        $reference = Reference::firstWhere('title', 'Galerie');
        $image = $reference->images->first();
        $path = public_path($image->path);
        foreach ($reference->images as $i) {
            $this->track($i->path);
        }

        $this->delete(route('reference.image.destroy', [$reference, $image]));

        $this->assertFileDoesNotExist($path);
        $this->assertSame(1, $reference->fresh()->images()->count());
    }

    public function test_gallery_image_of_another_reference_cannot_be_deleted(): void
    {
        $this->actingAs($this->admin());

        $a = Reference::create(['title' => 'A', 'category' => 'stity']);
        $b = Reference::create(['title' => 'B', 'category' => 'stity']);
        $image = ReferenceImage::create(['reference_id' => $b->id, 'path' => 'images/reference/stity/x.jpg']);

        $this->delete(route('reference.image.destroy', [$a, $image]))->assertNotFound();

        $this->assertDatabaseHas('reference_images', ['id' => $image->id]);
    }

    public function test_deleting_a_reference_removes_its_images(): void
    {
        $this->actingAs($this->admin());

        $this->post('/admin/reference', [
            'title' => 'Smazat',
            'category' => 'garaze',
            'images' => [UploadedFile::fake()->image('a.jpg')],
        ]);

        $reference = Reference::firstWhere('title', 'Smazat');
        $path = public_path($reference->images->first()->path);

        $this->delete(route('reference.destroy', $reference));

        $this->assertFileDoesNotExist($path);
        $this->assertSame(0, Reference::count());
        $this->assertSame(0, ReferenceImage::count());
    }
}
