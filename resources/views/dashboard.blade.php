<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h1>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-16">
            <div class="db_card db_welcome">
                <p class="text-lg">{{ __("Hallo,") }}</p>
                @if (Auth::user()->dozent)
                    <p class="ml-6 font-bold text-xl inline-block">{{ Auth::user()->dozent->dozent_vorname }} {{ Auth::user()->dozent->dozent_nachname }}</p>
                @else
                    <p class="ml-6 font-bold text-xl inline-block">{{ Auth::user()->name }}</p>
                @endif
                <p class="inline-block text-xl">!</p>
            </div>

            <div class="db_card">
                <h2>{{ __("Übersicht") }}</h2>
                <div class="db_shortcut_cont">
                    @if (Auth::user()->dozent)
                        <x-primary-button-link href="{{ route('stundenplan.my') }}">
                            <img class="db_shortcut_icon" src="{{ route('image.show', 'noun-calendar-5490924.svg') }}"
                                 title="Stundenplan von '{{ Auth::user()->dozent->dozent_nachname }}, {{ Auth::user()->dozent->dozent_vorname}}'"
                                 alt="Stundenplan von '{{ Auth::user()->dozent->dozent_nachname }}, {{ Auth::user()->dozent->dozent_vorname}}'">
                            <p>{{ __('Mein Stundenplan') }}</p>
                        </x-primary-button-link>
                    @endif
                    <x-primary-button-link href="{{ route('profile.edit') }}">
                        <img class="db_shortcut_icon" src="{{ route('image.show', 'noun-user-6714086.svg') }}"
                             title="Profil von '{{ Auth::user()->name }}'" alt="Profil von '{{ Auth::user()->name }}'">
                        <p>{{ __('Mein Profil') }}</p>
                    </x-primary-button-link>
                </div>
                @if (Auth::user()->dozent)
                    <div class="db_statistics mt-4 flex items-start">
                        <p class="inline-block mr-4"><strong>Stundenplan Status</strong>:</p>
                        <div class="inline-block">
                            @if (Auth::user()->dozent->plan_abgegeben === 1)
                                <p><strong class="text-green-600">&#x2B24; Abgegeben</strong></p>
                            @elseif (Auth::user()->dozent->plan_abgegeben === 0)
                                <p><strong class="text-yellow-600">&#x2B24; In Arbeit</strong></p>
                            @else
                                <p><strong class="text-red-600">&#x2B24; Nicht abgegeben</strong></p>
                            @endif

                            @if (Auth::user()->dozent->plan_abgegeben !== 1)
                                @php
                                    $kurs_count = Auth::user()->dozent->kurse->count();
                                    $kurse_mit_stunden = Auth::user()->dozent->kurse->filter(function($kurs) {
                                        // filter all kurse with stunden count > 0
                                        return $kurs->stunden->count() > 0;
                                    })->count();
                                @endphp
                                <p class="ml-4"><strong class="font-medium">Kurse
                                        geplant:</strong> {{ $kurse_mit_stunden }} / {{ $kurs_count }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="db_card">
                <h2>{{ __("Stundenpläne") }}</h2>
                <div class="">
                    @foreach($studiengaenge->sortBy('stdg_name') as $stdg)
                        <details class="mb-2 details-admin db_stdgs" open>
                            <summary class="text-lg ml-2">{{ $stdg->stdg_name }}</summary>
                            <div class="db_shortcut_cont px-4 pb-3">
                                {{-- for each semester that exist stunden for this studiengang --}}
                                @foreach($stdg->kurse->sortBy('semester')->unique('semester') as $kurs)
                                    <x-primary-button-link
                                        href="{{ route('stundenplan.show_sem', [1, $stdg->id, $kurs->semester]) }}">
                                        <img class="db_shortcut_icon"
                                             src="{{ route('image.show', 'noun-calendar-5490924.svg') }}"
                                             title="Stundenplan von '{{ $stdg->stdg_name }}' im {{ $kurs->semester }}. Semester"
                                             alt="Stundenplan von '{{ $stdg->stdg_name }}' im {{ $kurs->semester }}. Semester">
                                        <p>{{ $stdg->stdg_kürzel }} <strong>{{ $kurs->semester }}</strong></p>
                                    </x-primary-button-link>
                                @endforeach
                                @if ($stdg->kurse->count() === 0)
                                    <p class="text-center">Keine Stundenpläne vorhanden</p>
                                @endif
                            </div>

                        </details>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
