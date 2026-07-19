<?php

namespace App\Http\Controllers;

use App\Models\Reference;
use App\Models\ReferenceImage;
use App\Services\ImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReferencesController extends Controller
{
    public function __construct(protected ImageUploader $uploader) {}

    public function index(): View
    {
        return view('admin.references.index', [
            'references' => Reference::with('images')->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.references.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, imagesRequired: true);

        $reference = Reference::create([
            'title' => $data['title'],
            'place' => $data['place'] ?? null,
            'category' => $data['category'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $this->storeImages($request, $reference);

        return to_route('reference.index')->with('success', 'Reference byla přidána.');
    }

    public function edit(Reference $reference): View
    {
        return view('admin.references.edit', ['reference' => $reference->load('images')]);
    }

    public function update(Request $request, Reference $reference): RedirectResponse
    {
        $data = $this->validated($request, imagesRequired: false);

        $reference->update([
            'title' => $data['title'],
            'place' => $data['place'] ?? null,
            'category' => $data['category'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $this->storeImages($request, $reference);

        return to_route('reference.index')->with('success', 'Reference byla upravena.');
    }

    public function destroy(Reference $reference): RedirectResponse
    {
        foreach ($reference->images as $image) {
            $this->uploader->delete($image->path);
        }

        // Vazba je nastavená na cascadeOnDelete, řádky obrázků zmizí s referencí.
        $reference->delete();

        return to_route('reference.index')->with('success', 'Reference byla smazána.');
    }

    /**
     * Smaže jeden obrázek galerie.
     */
    public function destroyImage(Reference $reference, ReferenceImage $image): RedirectResponse
    {
        if ($image->reference_id !== $reference->id) {
            abort(404);
        }

        $this->uploader->delete($image->path);
        $image->delete();

        return back()->with('success', 'Obrázek byl smazán.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request, bool $imagesRequired): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'place' => ['nullable', 'string', 'max:255'],
            'category' => ['required', Rule::in(array_keys(Reference::CATEGORIES))],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'images' => [$imagesRequired ? 'required' : 'nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'title.required' => 'Vyplňte název reference.',
            'category.required' => 'Vyberte kategorii.',
            'category.in' => 'Vybraná kategorie neexistuje.',
            'images.required' => 'Nahrajte alespoň jeden obrázek.',
            'images.*.image' => 'Všechny nahrané soubory musí být obrázky.',
            'images.*.max' => 'Každý obrázek může mít nejvýše 5 MB.',
        ]);
    }

    protected function storeImages(Request $request, Reference $reference): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $next = (int) $reference->images()->max('sort_order');

        foreach ($request->file('images') as $file) {
            $reference->images()->create([
                'path' => $this->uploader->store($file, 'images/reference/'.$reference->category),
                'sort_order' => ++$next,
            ]);
        }
    }
}
