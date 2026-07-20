@extends('layout')

@section('content')
<div x-data="{ sidebarOpen: false, userMenuOpen: false }" @keydown.escape.window="sidebarOpen = false; userMenuOpen = false">
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <button @click="sidebarOpen = !sidebarOpen" :aria-expanded="sidebarOpen ? 'true' : 'false'" aria-controls="logo-sidebar" type="button" class="inline-flex cursor-pointer items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-hidden focus:ring-2 focus:ring-gray-200">
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                        </svg>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="flex ms-2 md:me-24">
                        <img src="{{ asset('images/logo.png') }}" class="me-3" alt="JIRFA s.r.o." />
                    </a>
                </div>
                <div class="flex items-center">
                    <div class="flex items-center ms-3 gap-4">
                        <p class="hidden sm:flex items-center"><i class="fa-brands fa-php fa-lg me-2" style="color:#777BB4" aria-hidden="true"></i> <span>{{ phpversion() }}</span></p>
                        <p class="hidden sm:flex items-center"><i class="fa-brands fa-laravel fa-lg me-2" style="color:#FF2D20" aria-hidden="true"></i> {{ \Illuminate\Foundation\Application::VERSION }}</p>

                        <div class="relative" @click.outside="userMenuOpen = false">
                            <button type="button" class="flex cursor-pointer text-sm" :aria-expanded="userMenuOpen ? 'true' : 'false'" aria-controls="dropdown-user" @click="userMenuOpen = !userMenuOpen">
                                <img src="data:image/jpeg;base64,{{ \Illuminate\Support\Facades\Auth::user()->profile_picture }}" class="w-8 h-8 rounded-full" alt="{{ \Illuminate\Support\Facades\Auth::user()->name }}" />
                            </button>

                            <div x-show="userMenuOpen" x-cloak class="absolute right-0 z-50 mt-2 text-base list-none bg-white divide-y divide-gray-100 rounded-sm shadow-lg ring-1 ring-gray-200 w-[200px]" id="dropdown-user">
                                <div class="px-4 py-3" role="none">
                                    <p class="text-sm text-gray-900" role="none">
                                        {{ \Illuminate\Support\Facades\Auth::user()->name }}
                                    </p>
                                    <p class="text-sm font-medium text-gray-900 truncate" role="none">
                                        {{ \Illuminate\Support\Facades\Auth::user()->email }}
                                    </p>
                                </div>
                                <ul class="py-1" role="none">
                                    <li>
                                        <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Odhlásit se</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <aside id="logo-sidebar"
           class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 sm:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           aria-label="Sidebar">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
            <ul class="space-y-2 font-medium">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       @class([
                           'flex items-center p-2 rounded-lg group',
                           'bg-red-50 text-red-700' => request()->routeIs('admin.dashboard'),
                           'text-gray-900 hover:bg-gray-100' => ! request()->routeIs('admin.dashboard'),
                       ])>
                        <i class="fa-solid fa-gauge fa-lg text-red-600"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Přehled</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reference.index') }}"
                       @class([
                           'flex items-center p-2 rounded-lg group',
                           'bg-red-50 text-red-700' => request()->routeIs('reference.*'),
                           'text-gray-900 hover:bg-gray-100' => ! request()->routeIs('reference.*'),
                       ])>
                        <i class="fa-solid fa-heart fa-lg text-red-600"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Reference</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('certificate.index') }}"
                       @class([
                           'flex items-center p-2 rounded-lg group',
                           'bg-red-50 text-red-700' => request()->routeIs('certificate.*'),
                           'text-gray-900 hover:bg-gray-100' => ! request()->routeIs('certificate.*'),
                       ])>
                        <i class="fa-solid fa-medal fa-lg text-red-600"></i>
                        <span class="flex-1 ms-3 whitespace-nowrap">Certifikáty</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <div class="p-4 sm:ml-64">
        <div class="mt-[60px]">
            @if (session('gitOutput'))
                <details class="mb-4 rounded-lg border border-gray-200 bg-white p-4">
                    <summary class="cursor-pointer text-sm font-semibold text-gray-700">Výstup gitu</summary>
                    <pre class="mt-3 overflow-x-auto rounded bg-gray-900 p-3 text-xs text-gray-100">{{ session('gitOutput') }}</pre>
                </details>
            @endif

            @yield('admin')
        </div>
    </div>
</div>
@endsection
