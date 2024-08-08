<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Kurs hinzufügen
        </h2>
    </x-slot>

    <div class="flex flex-col sm:justify-center items-center mt-10 sm:pt-0 bg-gray-100">
        <div class="sm:max-w-3xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ route('kurse.register', $doz_id) }}">
                @csrf

                <div class="flex gap-20 flex-wrap justify-evenly">
                    <fieldset class="min-w-72 md:w-fit w-full">
                        <legend class="text-lg font-semibold">Kursdaten</legend>

                        {{-- Name --}}
                        <div class="mt-4">
                            <x-input-label for="kurs_name" :value="__('Name')" />
                            <x-text-input id="kurs_name" class="block mt-1 w-full" type="text" name="kurs_name" :value="old('kurs_name')" required autofocus autocomplete="kurs_name" />
                            <x-input-error :messages="$errors->get('kurs_name')" class="mt-2" />
                        </div>

                        {{-- Semester --}}
                        <div class="mt-4">
                            <x-input-label for="semester" :value="__('Semester')" />
                            <x-text-input id="semester" class="block mt-1 w-full" min="1" max="10" type="number" name="semester" :value="old('semester')" required autocomplete="semester" />
                            <x-input-error :messages="$errors->get('semester')" class="mt-2" />
                        </div>

                        {{-- Semesterwochenstunden (SWS) --}}
                        <div class="mt-4">
                            <x-input-label for="sws" :value="__('SWS')" />
                            <x-text-input id="sws" class="block mt-1 w-full" type="number" name="sws" :value="old('sws')" min="1" max="10" required autocomplete="sws" />
                            <x-input-error :messages="$errors->get('sws')" class="mt-2" />
                        </div>

                        {{-- Studiengang --}}
                        <div class="mt-4">
                            <x-input-label for="studiengang" :value="__('Studiengang')" />
                            <select id="studiengang" name="studiengang" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="" disabled selected>Bitte wählen</option>
                                @foreach ($studiengaenge as $stdg)
                                    <option value="{{ $stdg->id }}">{{ $stdg->stdg_kürzel }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('studiengang')" class="mt-2" />
                        </div>
                    </fieldset>
                </div>


                <div class="flex items-center justify-end mt-8">
                    <x-primary-button class="ms-4">
                        Kurs hinzufügen
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
