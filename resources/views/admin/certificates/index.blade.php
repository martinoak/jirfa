@extends('admin.layout')

@section('admin')
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Certifikáty</h1>
        <a href="{{ route('certificate.create') }}"
           class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
            <i class="fa-solid fa-plus me-1" aria-hidden="true"></i> Přidat certifikát
        </a>
    </div>

    @if ($certificates->isEmpty())
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center text-gray-500">
            Zatím tu není žádný certifikát.
        </div>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
            @foreach ($certificates as $certificate)
                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    <img src="{{ $certificate->url() }}" alt="{{ $certificate->title }}" class="h-48 w-full object-contain p-2">
                    <div class="border-t border-gray-100 p-3">
                        <p class="truncate text-sm font-semibold" title="{{ $certificate->title }}">{{ $certificate->title }}</p>
                        <p class="mt-1 text-xs text-gray-500">Pořadí: {{ $certificate->sort_order }}</p>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('certificate.edit', $certificate) }}"
                               class="flex-1 rounded-md bg-gray-100 px-3 py-1.5 text-center text-xs font-semibold text-gray-700 transition hover:bg-gray-200">
                                Upravit
                            </a>
                            <form action="{{ route('certificate.destroy', $certificate) }}" method="POST"
                                  onsubmit="return confirm('Opravdu smazat certifikát {{ $certificate->title }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="cursor-pointer rounded-md bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-100">
                                    Smazat
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
