<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="title" class="mb-1.5 block text-sm font-medium text-gray-700">
            Název <span class="text-red-600" aria-hidden="true">*</span>
        </label>
        <input type="text" id="title" name="title" required placeholder="Střecha"
               value="{{ old('title', $reference?->title) }}"
               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-red-500 focus:ring-2 focus:ring-red-100 focus:outline-hidden">
    </div>

    <div>
        <label for="place" class="mb-1.5 block text-sm font-medium text-gray-700">Místo</label>
        <input type="text" id="place" name="place" placeholder="Kolovraty"
               value="{{ old('place', $reference?->place) }}"
               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-red-500 focus:ring-2 focus:ring-red-100 focus:outline-hidden">
    </div>
</div>

<div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
    <div>
        <label for="category" class="mb-1.5 block text-sm font-medium text-gray-700">
            Kategorie <span class="text-red-600" aria-hidden="true">*</span>
        </label>
        <select id="category" name="category" required
                class="w-full cursor-pointer rounded-lg border border-gray-300 bg-white px-4 py-2.5 focus:border-red-500 focus:ring-2 focus:ring-red-100 focus:outline-hidden">
            @foreach (\App\Models\Reference::CATEGORIES as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $reference?->category) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="sort_order" class="mb-1.5 block text-sm font-medium text-gray-700">Pořadí</label>
        <input type="number" id="sort_order" name="sort_order" min="0"
               value="{{ old('sort_order', $reference?->sort_order ?? 0) }}"
               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-red-500 focus:ring-2 focus:ring-red-100 focus:outline-hidden">
        <p class="mt-1 text-xs text-gray-500">Nižší číslo se zobrazí dřív.</p>
    </div>
</div>

<div>
    <label for="images" class="mb-1.5 block text-sm font-medium text-gray-700">
        Obrázky @unless ($reference)<span class="text-red-600" aria-hidden="true">*</span>@endunless
    </label>
    <input type="file" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/webp"
           @unless ($reference) required @endunless
           class="w-full cursor-pointer rounded-lg border border-gray-300 px-4 py-2.5 file:mr-4 file:cursor-pointer file:rounded file:border-0 file:bg-gray-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold">
    <p class="mt-1 text-xs text-gray-500">
        Můžete vybrat víc souborů najednou. První obrázek slouží jako náhled na webu.
        @if ($reference) Nově vybrané obrázky se přidají k současným. @endif
    </p>
</div>
