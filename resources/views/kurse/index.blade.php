<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kurse') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @foreach ($dozenten as $dozent)
                <details>
                    <summary>{{ $dozent->dozent_nachname }}, {{ $dozent->dozent_vorname }}</summary>
                    <div>
                        @if (is_null($dozent->kurse))
                            <p>Keine Fächer eingetragen.</p>
                        @else
                            <ul>
                                @foreach ($dozent->kurse as $kurs)
                                    <li>
                                        <strong>Kurs Name:</strong> {{ $kurs->kurs_name }}<br>
                                        <strong>Semester:</strong> {{ $kurs->semester }}<br>
                                        <strong>SWS:</strong> {{ $kurs->sws }}<br>
                                        <strong>Studiengang:</strong> {{ $kurs->kurs_name }}<br>
                                        <button>Fach l&ouml;schen</button>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <a href="#">&#x2795; Fach hinzuf&uuml;gen</a>
                    </div>
                </details>
            @endforeach

        </div>
    </div>
</x-app-layout>
