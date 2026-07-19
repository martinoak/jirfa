@extends('admin.layout')

@section('admin')
    <div class="mx-auto max-w-2xl">
        <h1 class="mb-6 text-2xl font-semibold">Nový certifikát</h1>

        @include('partials.form-errors')

        <form action="{{ route('certificate.store') }}" method="POST" enctype="multipart/form-data"
              class="space-y-5 rounded-lg border border-gray-200 bg-white p-6">
            @csrf

            @include('admin.certificates._fields', ['certificate' => null])

            <div class="flex gap-3">
                <button type="submit" class="cursor-pointer rounded-lg bg-red-600 px-5 py-2.5 font-semibold text-white transition hover:bg-red-700">
                    Uložit
                </button>
                <a href="{{ route('certificate.index') }}" class="rounded-lg bg-gray-100 px-5 py-2.5 font-semibold text-gray-700 transition hover:bg-gray-200">
                    Zpět
                </a>
            </div>
        </form>
    </div>
@endsection
