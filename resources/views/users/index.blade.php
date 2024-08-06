<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Benutzer & Dozenten') }}
        </h2>
    </x-slot>
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
                                        <a href="#">&#x2795; Hinzufügen</a>
                                    </td>
                                @else
                                <td>
                                    <div class="user_grid">
                                        <p class="user_grid_left"><strong>Username</strong>:</p> <p>{{ $user->name }}</p>
                                        <p class="user_grid_left"><strong>E-Mail</strong>:</p> <a class="underline" href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                    </div>
                                </td>
                                @endif
                                @if (is_null($user->d_id))
                                    <td class="text-center align-middle">
                                        <a href="#">&#x2795; Hinzufügen</a>
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
                                        <a href="#">
                                            <img class="admin-users-icons" src="{{ route('image.show', 'noun-edit-1047822.svg') }}" title="'{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}' bearbeiten" alt="'{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}' bearbeiten">
                                        </a>
                                        <button>
                                            <img class="admin-users-icons" src="{{ route('image.show', 'noun-trash-2025467.svg') }}" title="'{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}' löschen" alt="'{{ $user->dozent_nachname }}, {{ $user->dozent_vorname}}' löschen">
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>
                                <a class="w-full h-full" href="#">
                                    <p>&#x2795; Neuen User erstellen</p>
                                </a>
                            </td>
                            <td colspan="2">
                                <a class="w-full h-full" href="#">
                                    <p>&#x2795; Neuen Dozenten erstellen</p>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
