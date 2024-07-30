<!--Kurse-->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kurse') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @foreach ($dozenten as $dozent)
                <details class="mb-2 details-admin">
                    <summary class="text-lg ml-2">{{ $dozent->dozent_nachname }}, {{ $dozent->dozent_vorname }}</summary>
                    <div class="pl-4">
                        <a class="italic pb-2" href="#">&#x2795; Fach hinzuf&uuml;gen</a>
                        @if (is_null($dozent->kurse))
                            <p>Keine Fächer eingetragen.</p>
                        @else
                            <ul>
                                @foreach ($dozent->kurse as $kurs)
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                        <strong>Kurs Name</strong>: {{ $kurs->kurs_name }}<br>
                                                        <strong>Semester</strong>: {{ $kurs->semester }}<br>
                                                        <strong>SWS</strong>: {{ $kurs->sws }}<br>
                                                        <strong>Studiengang</strong>: {{ $kurs->studiengang->stdg_name }}
                                                </td>
                                                <td class="text-right">
                                                    <button class= "align-middle mr-4">&#x1F5D1; Löschen</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </details>
            @endforeach

        </div>
    </div>
</x-app-layout>
