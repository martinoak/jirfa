<div>
    <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700">
        Název <span class="text-red-600" aria-hidden="true">*</span>
    </label>
    <input type="text" id="title" name="title" required
           value="{{ old('title', $certificate?->title) }}"
           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-red-500 focus:ring-2 focus:ring-red-100 focus:outline-hidden">
</div>

<div>
    <label for="image" class="mb-1.5 block text-sm font-medium text-gray-700">
        Obrázek @unless ($certificate)<span class="text-red-600" aria-hidden="true">*</span>@endunless
    </label>
    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp"
           @unless ($certificate) required @endunless
           class="w-full cursor-pointer rounded-lg border border-gray-300 px-4 py-2.5 file:mr-4 file:cursor-pointer file:rounded file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold">
    <p class="mt-1 text-xs text-gray-500">
        JPG, PNG nebo WebP, nejvýše 5 MB.
        @if ($certificate) Ponechte prázdné, pokud chcete obrázek zachovat. @endif
    </p>
</div>

<div>
    <label for="sort_order" class="mb-1.5 block text-sm font-medium text-gray-700">Pořadí</label>
    <input type="number" id="sort_order" name="sort_order" min="0"
           value="{{ old('sort_order', $certificate?->sort_order ?? 0) }}"
           class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-red-500 focus:ring-2 focus:ring-red-100 focus:outline-hidden">
    <p class="mt-1 text-xs text-gray-500">Nižší číslo se zobrazí dřív.</p>
</div>
