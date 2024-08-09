<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($studiengang) ? __('Studiengang bearbeiten') : __('Studiengang hinzufügen')}}
        </h2>
    </x-slot>

    <div class="flex flex-col sm:justify-center items-center mt-10 sm:pt-0 bg-gray-100">
        <div class="sm:max-w-3xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <form method="POST" action="{{ isset($studiengang) ? route('stdgs.update', $studiengang->id) : route('stdgs.register') }}">
                @if (isset($studiengang))
                    @method('PATCH')
                @endif
                @csrf

                <div class="flex gap-20 flex-wrap justify-evenly">
                    <fieldset class="min-w-72 md:w-fit w-full">
                        <legend class="text-lg font-semibold">{{ __('Studiengangsdaten') }}</legend>

                        {{-- Name --}}
                        <div class="mt-4">
                            <x-input-label for="stdg_name" :value="__('Name')" />
                            <x-text-input id="stdg_name" class="block mt-1 w-full" type="text" name="stdg_name" :value="old('stdg_name', $studiengang->stdg_name ?? '')" required autofocus autocomplete="stdg_name" />
                            <x-input-error :messages="$errors->get('stdg_name')" class="mt-2" />
                        </div>

                        {{-- Shorthand --}}
                        <div class="mt-4">
                            <x-input-label for="stdg_short" :value="__('Kürzel')" />
                            <x-text-input id="stdg_short" class="block mt-1 w-full" type="text" name="stdg_short" :value="old('stdg_short', $studiengang->stdg_kürzel ?? '')" required autocomplete="stdg_short" />
                            <x-input-error :messages="$errors->get('stdg_short')" class="mt-2" />
                        </div>
                    </fieldset>
                </div>


                <div class="flex items-center justify-end mt-8">
                    <x-primary-button class="ms-4">
                        {{ isset($studiengang) ? __('Studiengang aktualisieren') : __('Studiengang hinzufügen') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
