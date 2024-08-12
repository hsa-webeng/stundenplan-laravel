<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mein Stundenplan') }}
        </h2>
    </x-slot>

    @include('components.status-msg-js')

    <div class="py-12 relative">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 fixed timetable_page sidebar_page w-full">
            <aside class="sidebar col-start-1">
                <div class="sidebar_header">
                    <h2 class="font-bold text-lg">{{ __('Fächerwahl') }}</h2>
                    {{--
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 57.802 57.802">
                        <path id="block_24dp_FILL0_wght400_GRAD0_opsz24"
                              d="M108.9-822.2a28.143,28.143,0,0,1-11.271-2.276,29.186,29.186,0,0,1-9.176-6.178,29.185,29.185,0,0,1-6.178-9.176A28.143,28.143,0,0,1,80-851.1a28.142,28.142,0,0,1,2.276-11.271,29.186,29.186,0,0,1,6.178-9.176,29.187,29.187,0,0,1,9.176-6.178A28.143,28.143,0,0,1,108.9-880a28.143,28.143,0,0,1,11.271,2.276,29.187,29.187,0,0,1,9.176,6.178,29.186,29.186,0,0,1,6.178,9.176A28.143,28.143,0,0,1,137.8-851.1a28.143,28.143,0,0,1-2.276,11.271,29.185,29.185,0,0,1-6.178,9.176,29.186,29.186,0,0,1-9.176,6.178A28.143,28.143,0,0,1,108.9-822.2Zm0-5.78a22.582,22.582,0,0,0,7.514-1.264,22.977,22.977,0,0,0,6.647-3.649L90.693-865.26a22.977,22.977,0,0,0-3.649,6.647A22.582,22.582,0,0,0,85.78-851.1,22.314,22.314,0,0,0,92.5-834.7,22.314,22.314,0,0,0,108.9-827.978Zm18.208-8.959a22.977,22.977,0,0,0,3.649-6.647,22.582,22.582,0,0,0,1.264-7.514,22.314,22.314,0,0,0-6.719-16.4,22.314,22.314,0,0,0-16.4-6.719,22.582,22.582,0,0,0-7.514,1.264,22.977,22.977,0,0,0-6.647,3.649Z"
                              transform="translate(-80 880)" fill="#393939" />
                    </svg>
                    --}}
                </div>
                <div class="sidebar_courses">
                    @foreach(Auth::user()->dozent->kurse as $kurs)
                        {{-- check if kurs->id is in $stunden & set draggable to false if it is --}}
                        <div class="subject" draggable="{{ $stunden->contains('kurs_id', $kurs->id) ? 'false' : 'true' }}" data-length="{{ $kurs->sws / 2 }}" data-subject-id="{{ $kurs->id }}">
                            <p class="course_name">{{ $kurs->kurs_name }}</p>
                            <p class="course_stdg">{{ $kurs->studiengang->stdg_kürzel }} {{ $kurs->semester }}</p>
                            <p class="course_sws">{{ $kurs->sws }} SWS</p>
                        </div>
                    @endforeach
                </div>
            </aside>
            <form class="col-start-1" id="timetableForm" method="POST" action="{{ route('stundenplan.save') }}">
                @csrf
                <input type="hidden" name="timetable_state" id="timetableState">
                <div class="flex items-center justify-end mt-8 mr-6">
                    <x-primary-button class="submit">
                        {{ __('Speichern') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 w-full">
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
                                                <div class="subject dragging dropped-subject color-1" data-subject-id="{{ $stunde->kurs->id }}" data-length="{{ $stunde->kurs->sws / 2 }}" data-start-time="{{ $stunde->block_start }}" data-end-time="{{ $stunde->block_end }}" data-day="{{ $stunde->wochentag }}" data-stunde-id="{{ $stunde->id }}" draggable="true">
                                                    <div class="time-span">{{ $stunde->block_start }} - {{ $stunde->block_end }}</div>
                                                    <p class="course_name">{{ $stunde->kurs->kurs_name }}</p>
                                                    <p class="course_stdg">{{ $stunde->kurs->studiengang->stdg_kürzel }} {{ $stunde->kurs->semester }}</p>
                                                </div>
                                            @elseif($stunde->wochentag == $i && strtotime($stunde->block_end) > strtotime($hours[0]) && strtotime($stunde->block_start) <= strtotime($hours[1]))
                                                <div class="subject dragging dropped-subject cloned color-1" data-subject-id="{{ $stunde->kurs->id }}" data-length="{{ $stunde->kurs->sws / 2 }}" data-start-time="{{ $stunde->block_start }}" data-end-time="{{ $stunde->block_end }}" data-day="{{ $stunde->wochentag }}" draggable="false">
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
