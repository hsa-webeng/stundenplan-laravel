<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Studiengänge') }}
        </h2>
    </x-slot>

    @include('components.status-msg')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="table_background">
                <table class="ausgabe-admin w-full">
                    <thead class="ausgabe-user-head">
                    <tr>
                        <th>Studiengang</th>
                        <th>Kürzel</th>
                        <th>Aktionen</th>
                    </tr>
                    </thead>
                    <tbody class="ausgabe-user-body">
                    @foreach($studiengaenge as $studiengang)
                        <tr>
                            <td>
                                {{ $studiengang->stdg_name }}
                            </td>
                            <td>
                                {{ $studiengang->stdg_kürzel }}
                            </td>
                            <td>
                                <div class="flex h-full items-center gap-3">
                                    <a href="#">
                                        <img class="admin-users-icons" src="{{ route('image.show', 'noun-edit-1047822.svg') }}" title="'{{ $studiengang->stdg_name }}' bearbeiten" alt="'{{ $studiengang->stdg_name }}' bearbeiten">
                                    </a>

                                    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-course-deletion-d-{{ $studiengang->id }}')">
                                        <img class="admin-users-icons" src="{{ route('image.show', 'noun-trash-2025467.svg') }}" title="'{{ $studiengang->stdg_name }}' löschen" alt="'{{ $studiengang->stdg_name }}' löschen">
                                    </x-danger-button>
                                    <x-modal name="confirm-course-deletion-d-{{ $studiengang->id }}" :show="$errors->userDeletion->isNotEmpty()" focusable>
                                        <form method="post" action="{{ route('studiengaenge.destroy', $studiengang->id) }}" class="p-6">
                                            @csrf
                                            @method('delete')

                                            <h2 class="text-lg font-medium text-gray-900">
                                                {{ __('Möchtest du den Studiengang "' . $studiengang->stdg_name . '" wirklich löschen?') }}
                                            </h2>

                                            <p class="mt-1 text-sm text-gray-600">
                                                {{ __('Dies wird den Studiengang, alle Kurse des Studiengangs & alle geplante Stunden löschen:') }}
                                            </p>

                                            <ul class="mt-1 text-sm">
                                                <li class="mt-1">{{ __('Studiengang: ' . $studiengang->stdg_name) }}</li>
                                                <li class="mt-1">{{ __('Alle Kurse des Studiengangs') }}</li>
                                                <li class="mt-1">{{ __('Geplante Stunden dieser Kurse') }}</li>
                                            </ul>

                                            <div class="mt-6 flex justify-end">
                                                <x-secondary-button x-on:click="$dispatch('close')">
                                                    {{ __('Abbrechen') }}
                                                </x-secondary-button>

                                                <x-danger-button class="ms-3">
                                                    {{ __('Studiengang löschen') }}
                                                </x-danger-button>
                                            </div>
                                        </form>
                                    </x-modal>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3">
                            <a class="w-full h-full" href="{{ route('stdgs.create') }}">
                                <p>&#x2795; Neuen Studiengang erstellen</p>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
