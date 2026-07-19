@extends('admin.layout')

@section('admin')
    <div class="mx-auto max-w-3xl">
        <h1 class="mb-6 text-2xl font-semibold">Upravit referenci</h1>

        @include('partials.form-errors')

        <form action="{{ route('reference.update', $reference) }}" method="POST" enctype="multipart/form-data"
              class="space-y-5 rounded-lg border border-gray-200 bg-white p-6">
            @csrf
            @method('PUT')

            @include('admin.references._fields', ['reference' => $reference])

            <div class="flex gap-3">
                <button type="submit" class="cursor-pointer rounded-lg bg-red-600 px-5 py-2.5 font-semibold text-white transition hover:bg-red-700">
                    Uložit změny
                </button>
                <a href="{{ route('reference.index') }}" class="rounded-lg bg-gray-100 px-5 py-2.5 font-semibold text-gray-700 transition hover:bg-gray-200">
                    Zpět
                </a>
            </div>
        </form>

        @if ($reference->images->isNotEmpty())
            <div class="mt-6 rounded-lg border border-gray-200 bg-white p-6">
                <h2 class="mb-4 text-lg font-semibold">Obrázky galerie ({{ $reference->images->count() }})</h2>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    @foreach ($reference->images as $image)
                        <div class="overflow-hidden rounded-lg border border-gray-200">
                            <img src="{{ $image->url() }}" alt="" class="h-28 w-full object-cover">
                            <form action="{{ route('reference.image.destroy', [$reference, $image]) }}" method="POST"
                                  onsubmit="return confirm('Opravdu smazat tento obrázek?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full cursor-pointer bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700 transition hover:bg-red-100">
                                    Smazat
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
