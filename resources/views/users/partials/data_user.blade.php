<fieldset class="min-w-72 md:w-fit w-full">
    <legend class="text-lg font-semibold">Benutzerdaten</legend>

    {{-- Name --}}
    <div class="mt-4">
        <x-input-label for="name" :value="__('Username')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name ?? '')" required autofocus autocomplete="name" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    {{-- Email Address --}}
    <div class="mt-4">
        <x-input-label for="email" :value="__('E-Mail')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email ?? '')" required autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    {{-- Password --}}
    <div class="mt-4">
        @if (isset($user))
            <x-input-label for="password" :value="__('Neues Passwort')" />

            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          autocomplete="new-password" />

        @else
        <x-input-label for="password" :value="__('Passwort')" />

        <x-text-input id="password" class="block mt-1 w-full"
                      type="password"
                      name="password"
                      required autocomplete="new-password" />
        @endif

        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    {{-- Confirm Password --}}
    <div class="mt-4">
        @if (isset($user))
            <x-input-label for="password_confirmation" :value="__('Neues Passwort wiederholen')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" autocomplete="new-password" />

        @else
        <x-input-label for="password_confirmation" :value="__('Passwort wiederholen')" />

        <x-text-input id="password_confirmation" class="block mt-1 w-full"
                      type="password"
                      name="password_confirmation" required autocomplete="new-password" />
        @endif

        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    {{-- Role --}}
    <div class="mt-4">
        <x-input-label for="role" :value="__('Rolle')" />
        <select id="role" name="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" required>
            <option value="0" {{ old('role', $user->admin ?? '') == 0 ? 'selected' : '' }}>Benutzer</option>
            <option value="1" {{ old('role', $user->admin ?? '') == 1 ? 'selected' : '' }}>Admin</option>
        </select>
        <x-input-error :messages="$errors->get('role')" class="mt-2" />
    </div>
</fieldset>
