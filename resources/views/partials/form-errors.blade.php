@if ($errors->any())
    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4" role="alert">
        <p class="mb-1 font-semibold text-red-800">Formulář se nepodařilo uložit:</p>
        <ul class="list-inside list-disc space-y-1 text-sm text-red-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
