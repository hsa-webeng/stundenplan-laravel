<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if ($mode === 1)
            {{ isset($user) ? __('Benutzer bearbeiten') : __('Benutzer erstellen')}}
            @elseif ($mode === 2)
            {{ isset($dozent) ? __('Dozent bearbeiten') : __('Dozent erstellen')}}
            @else
            {{ (isset($user) && isset($dozent)) ? __('Benutzer & Dozent bearbeiten') : __('Benutzer & Dozent erstellen')}}
            @endif
        </h2>
    </x-slot>

    <div class="flex flex-col sm:justify-center items-center mt-10 sm:pt-0 bg-gray-100">
        <div class="sm:max-w-3xl mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                @if (isset($user) || isset($dozent))
                    <form method="POST" action="{{ route('users.update', [$id, $mode]) }}">
                    @method('PATCH')
                @else
                    <form method="POST" action="{{ route('users.register', [$mode, $id]) }}">
                @endif

                @csrf

                <div class="flex gap-20 flex-wrap justify-evenly">
                    @if ($mode !== 2)
                         @if (isset($user))
                            @include('users.partials.data_user', ['user' => $user])
                        @else
                            @include('users.partials.data_user')
                        @endif
                    @endif

                    @if ($mode !== 1)
                        @if (isset($dozent))
                            @include('users.partials.data_doz', ['dozent' => $dozent])
                        @else
                            @include('users.partials.data_doz')
                        @endif
                    @endif
                </div>


                <div class="flex items-center justify-end mt-8">
                    <x-primary-button class="ms-4">
                        @if ($mode === 1)
                            {{ isset($user) ? __('Benutzer aktualisieren') : __('Benutzer erstellen')}}
                        @elseif ($mode === 2)
                            {{ isset($dozent) ? __('Dozent aktualisieren') : __('Dozent erstellen')}}
                        @else
                            {{ (isset($user) && isset($dozent)) ? __('Benutzer & Dozent aktualisieren') : __('Benutzer & Dozent erstellen')}}
                        @endif
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
