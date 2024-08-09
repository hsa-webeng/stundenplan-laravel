<fieldset class="min-w-72 md:w-fit w-full">
    <legend class="text-lg font-semibold">Dozentendaten</legend>

    {{-- First Name --}}
    <div class="mt-4">
        <x-input-label for="first_name" :value="__('Vorname')" />
        <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name', $dozent->dozent_vorname ?? '')" required autofocus autocomplete="first_name" />
        <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
    </div>

    {{-- Last Name --}}
    <div class="mt-4">
        <x-input-label for="last_name" :value="__('Nachname')" />
        <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name', $dozent->dozent_nachname ?? '')" required autocomplete="last_name" />
        <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
    </div>
</fieldset>
