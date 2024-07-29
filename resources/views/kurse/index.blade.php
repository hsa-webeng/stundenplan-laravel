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
                    <summary>{{ $dozent->dozent_nachname }} {{ $dozent->dozent_vorname }}</summary>
                    <div>
                        @if ($dozent->kurse->isEmpty())
                            <p>Keine Fächer eingetragen.</p>
                            <button>Fach hinzuf&uuml;gen</button>
                        @else
                            <ul>
                                @foreach ($dozent->kurse as $kurs)
                                    <li>
                                        <strong>Kurs Name:</strong> {{ $kurs->kurs_name }}<br>
                                        <strong>Semester:</strong> {{ $kurs->semester }}<br>
                                        <strong>SWS:</strong> {{ $kurs->sws }}<br>
                                        <strong>Studiengang:</strong> {{ $kurs->stdg_id }}
                                        <button>Fach l&ouml;schen</button>
                                    </li>
                                @endforeach
                            </ul>
                            <button href="#">Fach hinzuf&uuml;gen</button>
                        @endif
                    </div>
                </details>
            @endforeach

        </div>
    </div>
</x-app-layout>
