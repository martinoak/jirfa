<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\ImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CertificatesController extends Controller
{
    public function __construct(protected ImageUploader $uploader) {}

    public function index(): View
    {
        return view('admin.certificates.index', [
            'certificates' => Certificate::orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.certificates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'title.required' => 'Vyplňte název certifikátu.',
            'image.required' => 'Vyberte obrázek certifikátu.',
            'image.image' => 'Nahraný soubor musí být obrázek.',
            'image.max' => 'Obrázek může mít nejvýše 5 MB.',
        ]);

        Certificate::create([
            'title' => $data['title'],
            'image' => $this->uploader->store($request->file('image'), 'images/certificates'),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return to_route('certificate.index')->with('success', 'Certifikát byl přidán.');
    }

    public function edit(Certificate $certificate): View
    {
        return view('admin.certificates.edit', ['certificate' => $certificate]);
    }

    public function update(Request $request, Certificate $certificate): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $certificate->title = $data['title'];
        $certificate->sort_order = $data['sort_order'] ?? 0;

        if ($request->hasFile('image')) {
            $this->uploader->delete($certificate->image);
            $certificate->image = $this->uploader->store($request->file('image'), 'images/certificates');
        }

        $certificate->save();

        return to_route('certificate.index')->with('success', 'Certifikát byl upraven.');
    }

    public function destroy(Certificate $certificate): RedirectResponse
    {
        $this->uploader->delete($certificate->image);
        $certificate->delete();

        return to_route('certificate.index')->with('success', 'Certifikát byl smazán.');
    }
}
