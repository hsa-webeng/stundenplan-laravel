<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Benutzer & Dozenten') }}
        </h2>
    </x-slot>

    @include('components.status-msg')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="table_background">
                <table class="ausgabe-admin">
                    <thead class="ausgabe-user-head">
                    <tr>
                        <th>Benutzer</th>
                        <th>Dozent</th>
                        <th>Aktionen</th>
                    </tr>
                    </thead>
                    <tbody class="ausgabe-user-body">
                    @foreach ($users_dozenten as $user)
                        <tr>
                            @if (is_null($user->u_id))
                                <td class="text-center align-middle">
                                    <a href="{{ route('users.create', [1, $user->d_id]) }}">&#x2795; Hinzufügen</a>
                                </td>
                            @else
                                <td>
                                    <div class="user_grid">
                                        <p class="user_grid_left"><strong>Username</strong>:</p> <p>{{ $user->name }}</p>
                                        <p class="user_grid_left"><strong>E-Mail</strong>:</p> <a class="underline" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                        <p class="user_grid_left"><strong>Rolle</strong>:</p>
                                        @if ($user->admin == 1)
                                            <p><strong class="text-green-600">&#x2B24; Admin</strong></p>
                                        @elseif ($user->d_id)
                                            <p><strong class="text-slate-600">&#x2B24; Dozent</strong></p>
                                        @else
                                            <p><strong class="text-slate-600">&#x2B24; Benutzer</strong></p>
                                        @endif
                                    </div>
                                </td>
                            @endif
                            @if (is_null($user->d_id))
                                <td class="text-center align-middle">
                                    <a href="{{ route('users.create', [2, $user->u_id]) }}">&#x2795; Hinzufügen</a>
                                </td>
                            @else
                                <td>
                                    <div class="user_grid">
                                        <p class="user_grid_left"><strong>Name</strong>:</p> <p>{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}</p>
                                        <p class="user_grid_left"><strong>Status</strong>:</p>
                                        @if ($user->plan_abgegeben === 1)
                                            <p><strong class="text-green-600">&#x2B24; Abgegeben</strong></p>
                                        @elseif ($user->plan_abgegeben === 0)
                                            <p><strong class="text-yellow-600">&#x2B24; In Arbeit</strong></p>
                                        @elseif ($user->u_id === null)
                                            <p><strong class="text-slate-600">&#x2B24; Kein Benutzer</strong></p>
                                        @else
                                            <p><strong class="text-red-600">&#x2B24; Nicht abgegeben</strong></p>
                                        @endif
                                    </div>
                                </td>
                            @endif
                            <td>
                                <div class="flex h-full items-center gap-3">
                                    {{-- If selected is a dozent but has no user --}}
                                    @if (is_null($user->u_id) && $user->d_id)
                                        <a href="#">
                                            <img class="admin-users-icons" src="{{ route('image.show', 'noun-edit-1047822.svg') }}" title="'{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}' bearbeiten" alt="'{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}' bearbeiten">
                                        </a>

                                        <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion-d-{{ $user->d_id }}')">
                                            <img class="admin-users-icons" src="{{ route('image.show', 'noun-trash-2025467.svg') }}" title="'{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}' löschen" alt="'{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}' löschen">
                                        </x-danger-button>
                                        <x-modal name="confirm-user-deletion-d-{{ $user->d_id }}" :show="$errors->userDeletion->isNotEmpty()" focusable>
                                            <form method="post" action="{{ route('dozenten.destroy', $user->d_id) }}" class="p-6">
                                                @csrf
                                                @method('delete')

                                                <h2 class="text-lg font-medium text-gray-900">
                                                    {{ __('Möchtest du den Dozenten "' . $user->dozent_nachname . ', ' . $user->dozent_vorname . '" wirklich löschen?') }}
                                                </h2>

                                                <p class="mt-1 text-sm text-gray-600">
                                                    {{ __('Dies wird den Dozenten, alle Kurse & geplante Stunden des Dozenten löschen:') }}
                                                </p>

                                                <ul class="mt-1 text-sm">
                                                    <li class="mt-1">{{ __('Dozent: ') . $user->dozent_nachname . ', ' . $user->dozent_vorname }}</li>
                                                    <li class="mt-1">{{ __('Alle Kurse des Dozenten') }}</li>
                                                    <li class="mt-1">{{ __('Stundenplan des Dozenten') }}</li>
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

                                        {{-- If selected is a user (and a dozent) --}}
                                    @elseif($user->u_id)
                                        <a href="#">
                                            <img class="admin-users-icons" src="{{ route('image.show', 'noun-edit-1047822.svg') }}" title="'{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}' bearbeiten" alt="'{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}' bearbeiten">
                                        </a>

                                        <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion-{{ $user->u_id }}')">
                                            @if ($user->d_id)
                                                <img class="admin-users-icons" src="{{ route('image.show', 'noun-trash-2025467.svg') }}" title="'{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}' löschen" alt="'{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}' löschen">
                                            @else
                                                <img class="admin-users-icons" src="{{ route('image.show', 'noun-trash-2025467.svg') }}" title="'{{ $user->name }}' löschen" alt="'{{ $user->name }}' löschen">
                                            @endif
                                        </x-danger-button>
                                        <x-modal name="confirm-user-deletion-{{ $user->u_id }}" :show="$errors->userDeletion->isNotEmpty()" focusable>
                                            <form method="post" action="{{ route('users.destroy', $user->u_id) }}" class="p-6">
                                                @csrf
                                                @method('delete')

                                                <h2 class="text-lg font-medium text-gray-900">
                                                    @if ($user->d_id)
                                                        {{ __('Möchtest du den Dozenten "' . $user->dozent_nachname . ', ' . $user->dozent_vorname . '" wirklich löschen?') }}
                                                    @else
                                                        {{ __('Möchtest du den Benutzer "' . $user->name . '" wirklich löschen?') }}
                                                    @endif
                                                </h2>

                                                <p class="mt-1 text-sm text-gray-600">
                                                    @if ($user->d_id)
                                                        {{ __('Dies wird den Dozenten, alle Kurse & geplanten Stunden des Dozenten, den Benutzer und Anmeldedaten löschen:') }}
                                                    @else
                                                        {{ __('Dies wird den Benutzer und Anmeldedaten löschen:') }}
                                                    @endif
                                                </p>

                                                <ul class="mt-1 text-sm">
                                                    <li class="mt-1">{{ __('Benutzer: ') . $user->name }}</li>
                                                    <li class="mt-1">{{ __('E-Mail: ') . $user->email }}</li>
                                                    @if ($user->d_id)
                                                        <li class="mt-1">{{ __('Dozent: ') . $user->dozent_nachname . ', ' . $user->dozent_vorname }}</li>
                                                        <li class="mt-1">{{ __('Alle Kurse des Dozenten') }}</li>
                                                        <li class="mt-1">{{ __('Stundenplan des Dozenten') }}</li>
                                                    @endif
                                                </ul>

                                                <div class="mt-6 flex justify-end">
                                                    <x-secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('Abbrechen') }}
                                                    </x-secondary-button>

                                                    <x-danger-button class="ms-3">
                                                        @if ($user->d_id)
                                                            {{ __('Dozent & Benutzer löschen') }}
                                                        @else
                                                            {{ __('Benutzer löschen') }}
                                                        @endif
                                                    </x-danger-button>
                                                </div>
                                            </form>
                                        </x-modal>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3">
                            <div class="flex justify-between w-full">
                                <a class="h-full px-3" href="{{ route('users.create', 1) }}">
                                    <p>&#x2795; Neuen Benutzer erstellen</p>
                                </a>
                                <a class="h-full px-3" href="{{ route('users.create', 2) }}">
                                    <p>&#x2795; Neuen Dozenten erstellen</p>
                                </a>
                                <a class="h-full px-3" href="{{ route('users.create', 0) }}">
                                    <p>&#x2795; Neuen Benutzer & Dozenten erstellen</p>
                                </a>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
