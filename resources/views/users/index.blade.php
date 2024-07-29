<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Benutzer & Dozenten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <table>
                <thead>
                <tr>
                    <th>User</th>
                    <th>Dozent</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <p>{{ $user->name }}</p>
                            <p>{{ $user->email }}</p>
                        </td>
                        <td>
                            @if ($dozent->id->isEmpty())
                                <button href="#">Neuer Dozent</button>
                            @else
                                <p>{{ $user->dozent ? $user->dozent->dozent_nachname}} {{ $user->dozent ? $user->dozent->dozent_vorname}}</p>
                                <p>{{ $user->dozent ? $user->dozent->plan_abgegeben}}</p>
                                <button>Dozent löschen</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <button href="#">&#x2795; Neuer User</button>

        </div>
    </div>
</x-app-layout>
