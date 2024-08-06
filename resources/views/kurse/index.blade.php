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
                    <div class="p-4">
                        @if (is_null($dozent->kurse))
                            <p>Keine Fächer eingetragen.</p>
                        @else
                            <table class="ausgabe-admin w-full">
                                <thead class="ausgabe-user-head">
                                    <tr>
                                        <th>Kursname</th>
                                        <th>Semester</th>
                                        <th>SWS</th>
                                        <th>Studiengang</th>
                                        <th>Aktionen</th>
                                    </tr>
                                </thead>
                                <tbody class="ausgabe-user-body">
                                @foreach ($dozent->kurse as $kurs)
                                            <tr>
                                                <td>{{ $kurs->kurs_name }}</td>
                                                <td>{{ $kurs->semester }}</td>
                                                <td>{{ $kurs->sws }}</td>
                                                <td>{{ $kurs->studiengang->stdg_name }}</td>
                                                <td class="text-right">
                                                    <div class="flex h-full items-center gap-3">
                                                        <a href="#">
                                                            <img class="admin-users-icons" src="{{ route('image.show', 'noun-edit-1047822.svg') }}" title="'{{ $kurs->kurs_name }}' bearbeiten" alt="'{{ $kurs->kurs_name }}' bearbeiten">
                                                        </a>

                                                        <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-course-deletion-d-{{ $kurs->id }}')">
                                                            <img class="admin-users-icons" src="{{ route('image.show', 'noun-trash-2025467.svg') }}" title="'{{ $kurs->kurs_name }}' löschen" alt="'{{ $kurs->kurs_name }}' löschen">
                                                        </x-danger-button>
                                                        <x-modal name="confirm-course-deletion-d-{{ $kurs->id }}" :show="$errors->userDeletion->isNotEmpty()" focusable>
                                                            <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
                                                                @csrf
                                                                @method('delete')

                                                                <h2 class="text-lg font-medium text-gray-900">
                                                                    {{ __('Möchtest du den Kurs "' . $kurs->kurs_name . '" wirklich löschen?') }}
                                                                </h2>

                                                                <p class="mt-1 text-sm text-gray-600">
                                                                    {{ __('Dies wird den Kurs & alle geplante Stunden löschen:') }}
                                                                </p>

                                                                <ul class="mt-1 text-sm">
                                                                    <li class="mt-1">{{ __('Kurs des Dozenten') }}</li>
                                                                    <li class="mt-1">{{ __('Geplante Stunden des Kurses') }}</li>
                                                                </ul>

                                                                <div class="mt-6 flex justify-end">
                                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                                        {{ __('Abbrechen') }}
                                                                    </x-secondary-button>

                                                                    <x-danger-button class="ms-3">
                                                                        {{ __('Dozent löschen') }}
                                                                    </x-danger-button>
                                                                </div>
                                                            </form>
                                                        </x-modal>
                                                    </div>
                                                </td>
                                            </tr>
                                @endforeach
                                <tr>
                                    <td colspan="5">
                                        <a class="w-full h-full" href="#">
                                            <p>&#x2795; Neuen Kurs erstellen</p>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </details>
            @endforeach

        </div>
    </div>
</x-app-layout>
