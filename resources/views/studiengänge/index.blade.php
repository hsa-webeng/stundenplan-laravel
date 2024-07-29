<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Studiengänge') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <table>
                <thead>
                <tr>
                    <th>Studieng&auml;nge</th>
                    <th>Bearbeiten</th>
                    <th>L&ouml;schen</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($studiengänge as $studiengang)
                        <tr>
                            <td>
                                {{ $studiengang->stdg_name }} {{ $studiengang->stdg_kürzel }}
                            </td>
                            <td>Icon</td>
                            <td>Icon</td>
                            <!--Methoden zum Löschen und Bearbeiten-->
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</x-app-layout>
