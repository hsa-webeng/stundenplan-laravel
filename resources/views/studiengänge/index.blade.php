<!--Studiengänge-->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Studiengänge') }}
        </h2>
    </x-slot>

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
                                        <button>
                                            <img class="admin-users-icons" src="{{ route('image.show', 'noun-trash-2025467.svg') }}" title="'{{ $studiengang->stdg_name }}' löschen" alt="'{{ $studiengang->stdg_name }}' löschen">
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3">
                                <a class="w-full h-full" href="#">
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
