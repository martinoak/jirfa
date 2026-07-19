@extends('admin.layout')

@section('admin')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Reference</h1>
        <a href="{{ route('reference.create') }}"
           class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
            <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Přidat referenci
        </a>
    </div>

    @if ($references->isEmpty())
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center text-gray-500">
            Zatím tu není žádná reference.
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                <tr>
                    <th class="px-4 py-3">Náhled</th>
                    <th class="px-4 py-3">Název</th>
                    <th class="px-4 py-3">Kategorie</th>
                    <th class="px-4 py-3">Obrázků</th>
                    <th class="px-4 py-3">Pořadí</th>
                    <th class="px-4 py-3 text-right">Akce</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @foreach ($references as $reference)
                    <tr>
                        <td class="px-4 py-3">
                            @if ($reference->images->isNotEmpty())
                                <img src="{{ $reference->images->first()->url() }}" alt=""
                                     class="h-14 w-20 rounded object-cover">
                            @else
                                <span class="flex h-14 w-20 items-center justify-center rounded bg-gray-100 text-gray-400">
                                    <i class="fa-solid fa-image" aria-hidden="true"></i>
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-semibold text-gray-900">{{ $reference->title }}</span>
                            @if ($reference->place)
                                <span class="block text-xs text-gray-500">{{ $reference->place }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium">{{ $reference->categoryLabel() }}</span>
                        </td>
                        <td class="px-4 py-3">{{ $reference->images->count() }}</td>
                        <td class="px-4 py-3">{{ $reference->sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('reference.edit', $reference) }}"
                                   class="rounded-md bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-200">
                                    Upravit
                                </a>
                                <form action="{{ route('reference.destroy', $reference) }}" method="POST"
                                      onsubmit="return confirm('Opravdu smazat referenci {{ $reference->title }} včetně obrázků?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="cursor-pointer rounded-md bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-100">
                                        Smazat
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
