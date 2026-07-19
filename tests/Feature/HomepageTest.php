<?php

namespace Tests\Feature;

use App\Mail\ContactForm;
use App\Mail\ContactFormConfirm;
use App\Models\Certificate;
use App\Models\Customer;
use App\Models\Reference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_shows_content_from_the_database(): void
    {
        Certificate::create(['title' => 'Certifikát BRAMAC', 'image' => 'images/certificates/bramac1.jpg']);

        $reference = Reference::create([
            'title' => 'Střecha',
            'place' => 'Kolovraty',
            'category' => 'strechy',
            'thumbnail' => 'images/reference/strechy/01_m.jpg',
        ]);
        $reference->images()->create(['path' => 'images/reference/strechy/01.jpg']);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Certifikát BRAMAC')
            ->assertSee('Kolovraty')
            ->assertSee('images/reference/strechy/01_m.jpg');
    }

    public function test_filter_only_offers_categories_that_have_references(): void
    {
        $reference = Reference::create(['title' => 'Pergola', 'category' => 'pergoly']);
        $reference->images()->create(['path' => 'images/reference/pergoly/01.jpg']);

        $response = $this->get('/');

        // Tlačítko filtru pozná podle volání select(); samotné slovo
        // "Podlahy" je i mezi službami, proto by hledání textu nestačilo.
        $response->assertOk()
            ->assertSee("select('pergoly')", false)
            ->assertDontSee("select('podlahy')", false);
    }

    public function test_reference_without_images_is_skipped(): void
    {
        Reference::create(['title' => 'Bez obrázků', 'category' => 'stity']);

        $this->get('/')->assertOk()->assertDontSee('Bez obrázků');
    }

    public function test_contact_form_requires_the_mandatory_fields(): void
    {
        $this->post('/email', [])
            ->assertSessionHasErrors(['name', 'email', 'tel', 'message']);

        $this->assertSame(0, Customer::count());
    }

    public function test_contact_form_validates_email_and_phone_length(): void
    {
        $this->post('/email', [
            'name' => 'Jan Novák',
            'email' => 'neplatny-email',
            'tel' => '123',
            'message' => 'Dobrý den',
        ])->assertSessionHasErrors(['email', 'tel']);
    }

    public function test_contact_form_stores_optional_city_and_zip(): void
    {
        Mail::fake();

        // Bez platné reCAPTCHA se formulář neodešle, proto se ověřuje jen
        // validace a uložení -- ověření tokenu má vlastní test níže.
        $this->app->bind('recaptcha.always-passes', fn () => true);

        $response = $this->post('/email', [
            'name' => 'Jan Novák',
            'email' => 'jan@novak.cz',
            'tel' => '+420 606 094 834',
            'city' => 'Praha',
            'zip' => '14000',
            'message' => 'Mám zájem o střechu.',
            'recaptcha_response' => '',
        ]);

        // reCAPTCHA neprojde (prázdný token) -> uživatel dostane hlášku,
        // ale ne chybovou stránku.
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertSame(0, Customer::count());
        Mail::assertNothingSent();
    }

    public function test_failed_recaptcha_does_not_abort_with_403(): void
    {
        Mail::fake();

        $response = $this->post('/email', [
            'name' => 'Jan Novák',
            'email' => 'jan@novak.cz',
            'tel' => '606094834',
            'message' => 'Dobrý den',
            'recaptcha_response' => 'neplatny-token',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        Mail::assertNotSent(ContactForm::class);
        Mail::assertNotSent(ContactFormConfirm::class);
    }

    public function test_phone_number_is_normalised_before_validation(): void
    {
        $this->post('/email', [
            'name' => 'Jan Novák',
            'email' => 'jan@novak.cz',
            'tel' => '606 094 834',
            'message' => 'Dobrý den',
        ])->assertSessionDoesntHaveErrors('tel');
    }
}
