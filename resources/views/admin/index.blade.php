@extends('admin.layout')

@section('admin')
    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-3">
        <a href="{{ route('reference.index') }}" class="flex items-center gap-4 rounded-lg border border-gray-200 bg-white p-5 transition hover:shadow-md">
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-xl text-red-600">
                <i class="fa-solid fa-heart" aria-hidden="true"></i>
            </span>
            <span>
                <span class="block text-2xl font-semibold">{{ $referenceCount }}</span>
                <span class="block text-sm text-gray-500">Referencí</span>
            </span>
        </a>
        <a href="{{ route('certificate.index') }}" class="flex items-center gap-4 rounded-lg border border-gray-200 bg-white p-5 transition hover:shadow-md">
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-xl text-red-600">
                <i class="fa-solid fa-medal" aria-hidden="true"></i>
            </span>
            <span>
                <span class="block text-2xl font-semibold">{{ $certificateCount }}</span>
                <span class="block text-sm text-gray-500">Certifikátů</span>
            </span>
        </a>
        <div class="flex items-center gap-4 rounded-lg border border-gray-200 bg-white p-5">
            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-xl text-red-600">
                <i class="fa-solid fa-envelope" aria-hidden="true"></i>
            </span>
            <span>
                <span class="block text-2xl font-semibold">{{ $customers->count() }}</span>
                <span class="block text-sm text-gray-500">Poptávek</span>
            </span>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                Zákazníci z kontaktního formuláře
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Jméno</th>
                <th scope="col" class="px-6 py-3">E-mail</th>
                <th scope="col" class="px-6 py-3">Telefon</th>
                <th scope="col" class="px-6 py-3">Adresa</th>
                <th scope="col" class="px-6 py-3">Zpráva</th>
                <th scope="col" class="px-6 py-3">Přijato</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($customers as $customer)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-bold text-gray-900 whitespace-nowrap">
                        {{ $customer->name }}
                    </th>
                    <td class="px-6 py-4">
                        <a href="mailto:{{ $customer->email }}" class="text-blue-600 hover:underline">{{ $customer->email }}</a>
                    </td>
                    <td class="px-6 py-4">
                        <a href="tel:{{ $customer->tel }}" class="text-blue-600 hover:underline">{{ $customer->tel }}</a>
                    </td>
                    <td class="px-6 py-4">
                        {{ trim($customer->city.' '.$customer->zip) ?: '—' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $customer->message }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $customer->created_at?->format('d. m. Y H:i') ?? '—' }}
                    </td>
                </tr>
            @empty
                <tr class="bg-white border-b">
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Zatím nedorazila žádná poptávka.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
