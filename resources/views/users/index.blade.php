<!--User-->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Benutzer & Dozenten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a class="ausgabe-admin ml-4 italic" href="#" >&#x2795; Neuen User erstellen</a>
            <table class="ausgabe-admin w-full mt-4">
                <thead>
                <tr>
                    <th>User</th>
                    <th>Dozent</th>
                    <th>Bearbeiten</th>
                    <th>Löschen</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <p><strong>Name</strong>: {{ $user->name }}</p>
                            <p><strong>E-Mail</strong>: {{ $user->email }}</p>
                        </td>
                        @if (is_null($user->dozent))
                            <td class="text-center align-middle">
                                <a href="#" class="italic">&#x2795; Hinzufügen</a>
                            </td>
                        @else
                            <td>
                                <p>{{ $user->dozent->dozent_nachname }}, {{ $user->dozent->dozent_vorname}}</p>
                                <p>Status: {{ $user->dozent->plan_abgegeben }}</p>
                            </td>
                        @endif
                        <td class="text-center align-middle"><a href="#">&#x270F;</a></td>
                        <td class="text-center align-middle"><button>&#x1F5D1;</button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
