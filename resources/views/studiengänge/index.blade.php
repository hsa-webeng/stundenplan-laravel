<!--Studiengänge-->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Studiengänge') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <table class="ausgabe-admin w-full">
                <thead>
                <tr>
                    <th>Studieng&auml;nge</th>
                    <th>Kürzel</th>
                    <th>Bearbeiten</th>
                    <th>Löschen</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($studiengaenge as $studiengang)
                        <tr>
                            <td class="test_klasse">
                                {{ $studiengang->stdg_name }}
                            </td>
                            <td>
                                {{ $studiengang->stdg_kürzel }}
                            </td>
                            <td class="text-center align-middle"><a href="#">&#x270F;</a></td>
                            <td class="text-center align-middle"><button>&#x1F5D1;</button></td>
                            <!--Methoden zum Löschen und Bearbeiten-->
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</x-app-layout>
