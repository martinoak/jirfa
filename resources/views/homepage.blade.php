<!DOCTYPE html>
<html lang="cs">
<head>
    <!-- Povinné meta tagy -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Stavební firma JIRFA s.r.o. | Vaše střecha je náš problém!">
    <meta name="keywords" content="Stavební firma, střechy, pergoly, garáže, podlahy, Praha, JIRFA s.r.o.">
    <meta name="theme-color" content="#dc3545">

    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.key') }}"></script>
    <script>
        grecaptcha.ready(function() {
            document.getElementById('contactForm').addEventListener("submit", function(event) {
                event.preventDefault();
                grecaptcha.execute('{{ config('services.recaptcha.key') }}', { action: 'contact' }).then(function(token) {
                    document.getElementById("recaptchaResponse").value= token;
                    document.getElementById('contactForm').submit();
                });
            }, false);
        });
    </script>

    <title>JIRFA s.r.o | Vaše střecha je náš problém!</title>
</head>
<body class="font-sans text-gray-900 antialiased">
<a href="#sluzby" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[100] focus:rounded-md focus:bg-white focus:px-4 focus:py-2 focus:shadow-lg">
    Přeskočit na obsah
</a>

<header role="banner">
    <!-- Navigace -->
    <nav x-data="siteNav" @keydown.escape.window="closeAll()"
         class="fixed inset-x-0 top-0 z-50 transition-shadow duration-300"
         :class="scrolled ? 'bg-white/95 shadow-md backdrop-blur-sm' : 'bg-white'">
        <div class="container mx-auto px-4">
            <div class="flex h-16 items-center justify-between">
                <a href="#" class="shrink-0" aria-label="JIRFA s.r.o. — domů">
                    <img id="logo" src="{{ asset('images/logo.png') }}" width="108" height="24" alt="JIRFA s.r.o." class="w-28">
                </a>

                <button type="button"
                        @click="mobileOpen = !mobileOpen"
                        :aria-expanded="mobileOpen ? 'true' : 'false'"
                        aria-controls="mainMenu"
                        aria-label="Otevřít menu"
                        class="rounded-md p-2 text-gray-700 transition hover:bg-gray-100 lg:hidden">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Navigace pro velká zobrazení -->
                <ul class="hidden items-center gap-8 lg:flex">
                    <li><a href="#sluzby" class="font-semibold text-gray-700 transition hover:text-brand">Služby</a></li>
                    <li><a href="#certifikaty" class="font-semibold text-gray-700 transition hover:text-brand">Certifikáty</a></li>
                    <li class="relative" @click.outside="dropdownOpen = false">
                        <button type="button"
                                @click="dropdownOpen = !dropdownOpen"
                                :aria-expanded="dropdownOpen ? 'true' : 'false'"
                                aria-controls="referenceMenu"
                                class="flex items-center gap-1.5 font-semibold text-gray-700 transition hover:text-brand">
                            Reference
                            <svg class="h-3 w-3 transition-transform duration-200" :class="dropdownOpen && 'rotate-180'"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6"/>
                            </svg>
                        </button>
                        <ul id="referenceMenu"
                            x-show="dropdownOpen"
                            x-cloak
                            class="absolute left-0 z-50 mt-3 w-48 overflow-hidden rounded-lg bg-white py-2 shadow-xl ring-1 ring-gray-200">
                            @foreach (['strechy' => 'Střechy', 'podlahy' => 'Podlahy', 'garaze' => 'Garáže', 'pergoly' => 'Pergoly', 'stity' => 'Štíty'] as $value => $label)
                                <li>
                                    <a href="#reference"
                                       @click="dropdownOpen = false; $dispatch('filter-reference', '{{ $value }}')"
                                       class="block px-4 py-2 font-medium text-gray-700 transition hover:bg-brand hover:text-white">{{ $label }}</a>
                                </li>
                            @endforeach
                            <li><hr class="my-2 border-gray-200"></li>
                            <li>
                                <a href="#reference"
                                   @click="dropdownOpen = false; $dispatch('filter-reference', 'vse')"
                                   class="block px-4 py-2 font-medium text-gray-700 transition hover:bg-brand hover:text-white">Zobrazit vše</a>
                            </li>
                        </ul>
                    </li>
                    <li><a href="#kontakt" class="font-semibold text-gray-700 transition hover:text-brand">Kontakt</a></li>
                </ul>
            </div>

            <!-- Navigace pro malá zobrazení -->
            <ul id="mainMenu" x-show="mobileOpen" x-cloak
                class="border-t border-gray-200 py-3 lg:hidden">
                <li><a href="#sluzby" @click="closeAll()" class="block rounded-md px-2 py-2 font-semibold text-gray-700 hover:bg-gray-100">Služby</a></li>
                <li><a href="#certifikaty" @click="closeAll()" class="block rounded-md px-2 py-2 font-semibold text-gray-700 hover:bg-gray-100">Certifikáty</a></li>
                <li><a href="#reference" @click="closeAll()" class="block rounded-md px-2 py-2 font-semibold text-gray-700 hover:bg-gray-100">Reference</a></li>
                <li><a href="#kontakt" @click="closeAll()" class="block rounded-md px-2 py-2 font-semibold text-gray-700 hover:bg-gray-100">Kontakt</a></li>
            </ul>
        </div>
    </nav>
    <!-- Navigace -->

    <div class="hero flex min-h-[32rem] items-center justify-center pt-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl leading-tight font-extrabold text-white sm:text-5xl lg:text-6xl">
                Vaše střecha je náš problém!
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-lg text-white/90">
                Střechy, pergoly, garáže a dřevostavby na klíč. Cenovou nabídku zpracujeme zdarma.
            </p>
            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <a href="#reference"
                   class="inline-flex items-center gap-2 rounded-lg bg-brand px-8 py-4 text-lg font-bold text-white shadow-lg transition hover:-translate-y-0.5 hover:bg-brand-dark hover:shadow-xl">
                    Naše reference <i class="fa-solid fa-angles-down" aria-hidden="true"></i>
                </a>
                <a href="#kontakt"
                   class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-8 py-4 text-lg font-bold text-white ring-2 ring-white/70 backdrop-blur-sm transition hover:bg-white hover:text-gray-900">
                    Nezávazná poptávka
                </a>
            </div>
        </div>
    </div>
</header>

<main>
<section id="sluzby" class="scroll-mt-24 py-20">
    <div class="container mx-auto px-4">
        <h2 class="text-center text-3xl font-semibold sm:text-4xl">Čím se zabýváme</h2>
        <div class="mx-auto mt-4 h-1 w-20 rounded-full bg-brand"></div>

        <div class="mx-auto mt-10 max-w-4xl space-y-4 text-gray-700">
            <p><strong class="font-semibold text-gray-900">Stavební firma JIRFA, s.r.o. </strong>se specializuje na dodávky a montáže všech typů střech a krytin, pergol,
                garážových stání a dřevostaveb, včetně celkových oprav střešních plášťů, pokrývačských a klempířských
                prací a dále na veškeré sádrokartonářské práce. Také se zaměřujeme na opravu památkově chráněných
                objektů pod dozorem památkového úřadu.</p>
            <p>Na všechny tyto práce máme proškolené, odborně způsobilé zaměstnance, kteří pravidelně zvyšují svojí kvalifikaci.</p>
            <p>Firma JIRFA, s.r.o. má sídlo v Praze 4, ale pobočku také v Načeradci (okr. Benešov).
                V naší načeradské pobočce máme plně vybavenou truhlářskou dílnu (truhlářské a tesařské stroje a nářadí
                na výrobu hoblovaných pergol a vazeb). Součástí areálu jsou také sklady o rozloze 3000m2 , kde máme
                uskladněno a připraveno ihned k prodeji řezivo, střešní latě, prkna, fošny nebo hranoly.</p>
            <p>Stavební firma JIRFA, s.r.o. je držitelem certifikátů: <strong class="font-semibold text-gray-900">BRAMAC</strong>, <strong class="font-semibold text-gray-900">VELUX</strong>,
                <strong class="font-semibold text-gray-900">ISOVER</strong>, protipožární systémy <strong class="font-semibold text-gray-900">RIGIPS</strong>, hydropojistné fólie a další.
                JIRFA, s.r.o. má uzavřenou smlouvu na pojištění odpovědnosti za způsobenou škodu třetím osobám při
                výkonu své činnosti až do výše 10 mil.Kč. Všichni naši zaměstnanci jsou řádně proškoleni v oblasti
                bezpečnosti práce a práce ve výškách.</p>
            <p>V případě zájmu o naše služby Vám <strong class="font-semibold text-gray-900">zdarma</strong> vypracujeme cenovou nabídku.</p>
        </div>

        <div x-data class="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ([
                ['strechy', 'Střechy', 'fa-solid fa-house-chimney'],
                ['garaze', 'Garáže', 'fa-solid fa-warehouse'],
                ['podlahy', 'Podlahy', 'fa-solid fa-layer-group'],
                ['pergoly', 'Pergoly', 'fa-solid fa-store'],
                ['stity', 'Štíty', 'fa-solid fa-caret-up'],
                ['ostatni', 'Ostatní', 'fa-solid fa-plus'],
            ] as [$value, $label, $icon])
                <div class="group flex flex-col items-center rounded-xl border border-gray-200 bg-white p-8 text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand/30 hover:shadow-lg">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-brand-light text-3xl text-brand transition group-hover:bg-brand group-hover:text-white">
                        <i class="{{ $icon }}" aria-hidden="true"></i>
                    </div>
                    <h3 class="mt-5 text-xl font-semibold">{{ $label }}</h3>
                    <a href="#reference"
                       @click="$dispatch('filter-reference', '{{ $value }}')"
                       class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-brand transition hover:gap-2.5">
                        Reference <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="certifikaty" class="scroll-mt-24 bg-gray-50 py-20">
    <!-- TODO Přes administraci vykreslovat jednotlivé certifikáty -->
    <div class="container mx-auto px-4">
        <h2 class="text-center text-3xl font-semibold sm:text-4xl">Certifikáty</h2>
        <div class="mx-auto mt-4 h-1 w-20 rounded-full bg-brand"></div>

        @php
            $certificateUrls = $certificates->map(fn ($certificate) => $certificate->url())->all();
        @endphp

        <div x-data class="mt-12 grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($certificates as $index => $certificate)
                <button type="button"
                        @click="$store.lightbox.show({{ Js::from($certificateUrls) }}, {{ $index }}, '{{ $certificate->title }}')"
                        class="group cursor-pointer overflow-hidden rounded-lg border border-gray-200 bg-white p-2 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                        aria-label="Zobrazit certifikát {{ $index + 1 }}">
                    <img src="{{ $certificate->url() }}" width="174" height="239"
                         class="mx-auto h-auto w-full transition duration-300 group-hover:scale-105"
                         alt="{{ $certificate->title }}" loading="lazy">
                </button>
            @endforeach
        </div>
    </div>
</section>

@php
    // Ve filtru se zobrazí jen kategorie, ve kterých nějaká reference je.
    $usedCategories = $references->pluck('category')->unique();
    $categories = collect(['vse' => 'Vše'])
        ->merge(collect(\App\Models\Reference::CATEGORIES)->only($usedCategories));
@endphp

<section id="reference" class="scroll-mt-24 py-20"
         x-data="referenceFilter"
         @filter-reference.window="select($event.detail)">
    <div class="container mx-auto px-4">
        <h2 class="text-center text-3xl font-semibold sm:text-4xl">Reference</h2>
        <div class="mx-auto mt-4 h-1 w-20 rounded-full bg-brand"></div>

        <!-- Filtr kategorií -->
        <div class="mt-10 flex flex-wrap justify-center gap-2" role="group" aria-label="Filtr referencí">
            @foreach ($categories as $value => $label)
                <button type="button"
                        @click="select('{{ $value }}')"
                        :aria-pressed="selected === '{{ $value }}' ? 'true' : 'false'"
                        class="cursor-pointer rounded-full bg-gray-100 px-5 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-200 aria-pressed:bg-brand aria-pressed:text-white aria-pressed:shadow-md">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="mt-10 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($references as $reference)
                @continue ($reference->images->isEmpty())
                <div x-show="matches('{{ $reference->category }}')">
                    <button type="button"
                            @click="$store.lightbox.show({{ Js::from($reference->imageUrls()) }}, 0, '{{ $reference->fullTitle() }}')"
                            class="reference-card group relative block w-full cursor-pointer overflow-hidden rounded-xl shadow-md transition hover:shadow-xl"
                            aria-label="Zobrazit galerii: {{ $reference->fullTitle() }}">
                        <img src="{{ $reference->thumbnailUrl() }}"
                             width="361" height="271"
                             class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-110"
                             alt="{{ $reference->fullTitle() }}" loading="lazy">

                        <div class="reference-overlay absolute inset-0 flex flex-col items-center justify-center bg-brand/85 opacity-0 transition-opacity duration-300 group-hover:opacity-100 group-focus-visible:opacity-100">
                            <span class="text-2xl font-bold text-white">{{ $reference->title }}<br>{{ $reference->place }}</span>
                            <i class="fa-solid fa-magnifying-glass-plus mt-5 text-3xl text-white" aria-hidden="true"></i>
                            <span class="mt-3 text-sm font-medium text-white/90">Zobrazit ({{ $reference->images->count() }})</span>
                        </div>
                    </button>
                </div>
            @endforeach
        </div>

    </div>
</section>

<section id="dodavatele" class="scroll-mt-24 bg-gray-50 py-20">
    <div class="container mx-auto px-4">
        <h2 class="text-center text-3xl font-semibold sm:text-4xl">Partneři a dodavatelé</h2>
        <div class="mx-auto mt-4 h-1 w-20 rounded-full bg-brand"></div>

        <div class="mt-12 grid grid-cols-2 items-center justify-items-center gap-8 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ([['bramac', 'Bramac'], ['velux', 'Velux'], ['isover', 'Isover'], ['rigips', 'Rigips'], ['dek', 'DEK Trade'], ['jafholz', 'JAF Holz']] as [$file, $name])
                <img src="{{ asset("images/partners/{$file}.png") }}" width="156" height="98" alt="{{ $name }}" loading="lazy"
                     class="h-auto w-full max-w-[10rem] opacity-40 grayscale transition duration-300 hover:opacity-100 hover:grayscale-0">
            @endforeach
        </div>
    </div>
</section>

<section id="kontakt" class="scroll-mt-24 py-20">
    <div class="container mx-auto px-4">
        <h2 class="text-center text-3xl font-semibold sm:text-4xl">Kontakty</h2>
        <div class="mx-auto mt-4 h-1 w-20 rounded-full bg-brand"></div>

        <div class="mt-12 grid grid-cols-1 gap-8 lg:grid-cols-2">
            <!-- Údaje a mapa -->
            <div class="space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold">JIRFA, s.r.o.</h3>
                        <address class="mt-3 space-y-1 text-gray-700 not-italic">
                            <p>V Rovinách 55</p>
                            <p>140 00 Praha 4</p>
                        </address>
                        <p class="mt-3 flex items-center gap-2 text-gray-700">
                            <i class="fa-solid fa-briefcase text-brand" aria-hidden="true"></i> IČO: 27143287
                        </p>
                        <a href="https://jirfa.cz" class="mt-2 flex items-center gap-2 font-medium text-accent transition hover:underline">
                            <i class="fa-solid fa-globe" aria-hidden="true"></i>jirfa.cz
                        </a>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <h3 class="text-lg font-semibold">Jednatel</h3>
                        <p class="mt-3 text-gray-700">Jiří Šteker</p>
                        <a href="tel:+420606094834" class="mt-2 flex items-center gap-2 text-lg font-bold text-brand transition hover:underline">
                            <i class="fa-solid fa-phone-volume" aria-hidden="true"></i>+420 606 094 834
                        </a>
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2562.2016275805154!2d14.43143851593333!3d50.04505412437277!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470b940bab0bd0e1%3A0x3649bd01eb944614!2sV%20Rovin%C3%A1ch%2055%2C%20140%2000%20Praha%204!5e0!3m2!1scs!2scz!4v1644771196755!5m2!1scs!2scz"
                            class="h-72 w-full border-0" allowfullscreen="" loading="lazy" title="Sídlo firmy"></iframe>
                </div>
            </div>

            <!-- Poptávkový formulář -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm sm:p-8">
                <h3 class="text-2xl font-semibold">Ozvěte se nám!</h3>
                <p class="mt-2 text-gray-600">Cenovou nabídku zpracujeme zdarma a nezávazně.</p>

                @if (session('success'))
                    <div class="mt-4 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 p-4" role="status">
                        <i class="fa-solid fa-circle-check mt-0.5 text-green-600" aria-hidden="true"></i>
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mt-4 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-4" role="alert">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 text-red-600" aria-hidden="true"></i>
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4" role="alert">
                        <ul class="space-y-1 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('email') }}" method="post" id="contactForm" class="mt-6 space-y-4">
                    @csrf
                    <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Vaše jméno a příjmení <span class="text-brand" aria-hidden="true">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Jan Novák" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 transition focus:border-brand focus:ring-2 focus:ring-brand/20 focus:outline-hidden">
                    </div>

                    <div>
                        <label for="inputEmail" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Váš e-mail <span class="text-brand" aria-hidden="true">*</span>
                        </label>
                        <input type="email" id="inputEmail" name="email" value="{{ old('email') }}" placeholder="jan@novak.cz" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 transition focus:border-brand focus:ring-2 focus:ring-brand/20 focus:outline-hidden">
                    </div>

                    <div>
                        <label for="tel" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Váš telefon <span class="text-brand" aria-hidden="true">*</span>
                        </label>
                        <input type="tel" id="tel" name="tel" value="{{ old('tel') }}" placeholder="+420 123 456 789" required
                               class="w-full rounded-lg border border-gray-300 px-4 py-2.5 transition focus:border-brand focus:ring-2 focus:ring-brand/20 focus:outline-hidden">
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="inputCity" class="mb-1.5 block text-sm font-medium text-gray-700">Město</label>
                            <input type="text" id="inputCity" name="city" value="{{ old('city') }}"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 transition focus:border-brand focus:ring-2 focus:ring-brand/20 focus:outline-hidden">
                        </div>
                        <div>
                            <label for="inputZip" class="mb-1.5 block text-sm font-medium text-gray-700">PSČ</label>
                            <input type="text" id="inputZip" name="zip" value="{{ old('zip') }}"
                                   class="w-full rounded-lg border border-gray-300 px-4 py-2.5 transition focus:border-brand focus:ring-2 focus:ring-brand/20 focus:outline-hidden">
                        </div>
                    </div>

                    <div>
                        <label for="text" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Text zprávy <span class="text-brand" aria-hidden="true">*</span>
                        </label>
                        <textarea rows="5" id="text" name="message" placeholder="Dobrý den!" required
                                  class="w-full rounded-lg border border-gray-300 px-4 py-2.5 transition focus:border-brand focus:ring-2 focus:ring-brand/20 focus:outline-hidden">{{ old('message') }}</textarea>
                    </div>

                    <p class="text-xs text-gray-500"><span class="text-brand" aria-hidden="true">*</span> Povinné pole</p>

                    <button type="submit"
                            class="g-recaptcha w-full rounded-lg bg-brand px-6 py-3 font-semibold text-white shadow-md transition hover:bg-brand-dark hover:shadow-lg sm:w-auto">
                        Odeslat e-mail
                    </button>
                    <p class="font-bold" id="mailSuccess"></p>
                </form>
            </div>
        </div>
    </div>
</section>
</main>

<!-- Sdílený lightbox (nahrazuje lightbox2 + jQuery) -->
<div x-data
     x-show="$store.lightbox.open"
     x-cloak
     @keydown.escape.window="$store.lightbox.close()"
     @keydown.arrow-right.window="$store.lightbox.hasMultiple && $store.lightbox.next()"
     @keydown.arrow-left.window="$store.lightbox.hasMultiple && $store.lightbox.prev()"
     @click.self="$store.lightbox.close()"
     class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4"
     role="dialog" aria-modal="true" :aria-label="$store.lightbox.title">

    <button type="button" @click="$store.lightbox.close()" aria-label="Zavřít"
            class="absolute top-4 right-4 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-2xl text-white transition hover:bg-white/25">
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
    </button>

    <button type="button" x-show="$store.lightbox.hasMultiple" @click="$store.lightbox.prev()" aria-label="Předchozí"
            class="absolute left-4 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-2xl text-white transition hover:bg-white/25">
        <i class="fa-solid fa-angle-left" aria-hidden="true"></i>
    </button>

    <button type="button" x-show="$store.lightbox.hasMultiple" @click="$store.lightbox.next()" aria-label="Další"
            class="absolute right-4 flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-2xl text-white transition hover:bg-white/25">
        <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
    </button>

    <figure class="flex max-h-full flex-col items-center gap-4" @click.stop>
        <img :src="$store.lightbox.current" :alt="$store.lightbox.title"
             class="max-h-[80vh] w-auto max-w-full rounded-lg object-contain shadow-2xl">
        <figcaption class="text-center text-white">
            <span class="font-medium" x-text="$store.lightbox.title"></span>
            <span class="ml-2 text-sm text-white/70" x-show="$store.lightbox.hasMultiple">
                <span x-text="$store.lightbox.index + 1"></span>/<span x-text="$store.lightbox.images.length"></span>
            </span>
        </figcaption>
    </figure>
</div>

<!-- Footer -->
<footer id="footer" class="border-t border-gray-200 bg-white py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col items-center gap-3 text-center">
            <img src="{{ asset('images/logo.webp') }}" width="80" height="16" alt="JIRFA s.r.o." loading="lazy" class="w-20">
            <p class="text-sm text-gray-500">
                <strong>&copy;</strong> {{ date('Y') }} Martin Dub, JIRFA s.r.o.
            </p>
        </div>
    </div>
</footer>

<!-- Zpět nahoru -->
<button x-data="backToTop"
        x-show="visible"
        x-cloak
        @click="toTop()"
        type="button"
        title="Zpět nahoru"
        aria-label="Zpět nahoru"
        class="fixed right-5 bottom-24 z-50 flex h-12 w-12 items-center justify-center rounded-full bg-brand text-2xl text-white shadow-lg transition hover:bg-brand-dark">
    <i class="fa-solid fa-angle-up" aria-hidden="true"></i>
</button>
</body>
</html>
