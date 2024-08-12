<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if (Route::currentRouteName() === 'stundenplan.my')
                {{ __('Mein Stundenplan') }}
            @else
                {{ __('Stundenplan von ') }} {{ $dozent->dozent_nachname }}, {{ $dozent->dozent_vorname }}
            @endif
        </h2>
    </x-slot>

    @include('components.status-msg')

    <div class="py-12 relative">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 w-full">
            @if ($dozent != null)
                <div class="flex justify-between items-center p-2 mb-6 doz_status">
                    <p class="font-bold px-4"> Status:
                    @if ($dozent->plan_abgegeben === 1)
                        <strong class="text-green-600">&#x2B24; Abgegeben</strong>
                    @elseif ($dozent->plan_abgegeben === 0)
                        <strong class="text-yellow-600">&#x2B24; In Arbeit</strong>
                    @else
                        <strong class="text-red-600">&#x2B24; Nicht abgegeben</strong>
                    @endif
                    </p>

                    @if (Route::currentRouteName() === 'stundenplan.my')
                        <div class="flex gap-2 px-4">
                            @if ($dozent->plan_abgegeben === 1)
                                <x-danger-button-link class="submit" href="{{ route('stundenplan.submit', 0) }}">Abgabe rückgängig machen</x-danger-button-link>
                            @else
                                <x-primary-button-link class="submit" href="{{ route('stundenplan.edit') }}">Plan bearbeiten</x-primary-button-link>
                                <x-danger-button-link class="submit" href="{{ route('stundenplan.submit', 1) }}">Plan abgeben</x-danger-button-link>
                            @endif
                        </div>
                    @endif
                </div>
            @endif
            <div class="timetable_page w-full">
                <div class="timetable col-start-2">
                    <table class="timetable_content">
                        <thead class="timetable_head">
                            <tr>
                                <th>{{ __('Uhrzeit') }}</th>
                                @foreach ($days as $day)
                                    <th><?= $day ?></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($hours_pairs as $hours)
                            <tr>
                                <td class="timetable_time"><?= $hours[0] ?> - <?= $hours[1] ?></td>
                                @foreach ($days as $i => $day)
                                    <td class="timetable_data" data-time="<?=$hours[0]?>" data-day="<?=$day?>">
                                        {{-- check if there exists a course at this time in $stunden --}}
                                        @foreach ($stunden as $stunde)
                                            @if ($stunde->wochentag == $i && $stunde->block_start == $hours[0])
                                                <div class="subject dragging dropped-subject color-1" data-subject-id="{{ $stunde->kurs->id }}" data-length="{{ $stunde->kurs->sws / 2 }}" data-start-time="{{ $stunde->block_start }}" data-end-time="{{ $stunde->block_end }}" data-day="{{ $stunde->wochentag }}" data-stunde-id="{{ $stunde->id }}">
                                                    <div class="time-span">{{ $stunde->block_start }} - {{ $stunde->block_end }}</div>
                                                    <p class="course_name">{{ $stunde->kurs->kurs_name }}</p>
                                                    <p class="course_stdg">{{ $stunde->kurs->studiengang->stdg_kürzel }} {{ $stunde->kurs->semester }}</p>
                                                </div>
                                            @elseif($stunde->wochentag == $i && strtotime($stunde->block_end) > strtotime($hours[0]) && strtotime($stunde->block_start) <= strtotime($hours[1]))
                                                <div class="subject dragging dropped-subject cloned color-1" data-subject-id="{{ $stunde->kurs->id }}" data-length="{{ $stunde->kurs->sws / 2 }}" data-start-time="{{ $stunde->block_start }}" data-end-time="{{ $stunde->block_end }}" data-day="{{ $stunde->wochentag }}">
                                                    <div class="time-span">{{ $stunde->block_start }} - {{ $stunde->block_end }}</div>
                                                    <p class="course_name">{{ $stunde->kurs->kurs_name }}</p>
                                                    <p class="course_stdg">{{ $stunde->kurs->studiengang->stdg_kürzel }} {{ $stunde->kurs->semester }}</p>
                                                </div>
                                            @endif
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
