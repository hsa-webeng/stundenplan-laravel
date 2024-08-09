<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mein Stundenplan') }}
        </h2>
    </x-slot>

    @include('components.status-msg')

    <div class="py-12 relative">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 fixed timetable_page w-full">
            <aside class="sidebar">
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
                        <div class="subject" draggable="true" data-length="{{ $kurs->sws / 2 }}" data-subject-id="{{ $kurs->id }}">
                            <p class="course_name">{{ $kurs->kurs_name }}</p>
                            <p class="course_stdg">{{ $kurs->studiengang->stdg_kürzel }} {{ $kurs->semester }}</p>
                            <p class="course_sws">{{ $kurs->sws }} SWS</p>
                        </div>
                    @endforeach
                </div>
            </aside>
        </div>
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8 timetable_page w-full">
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
                            <td><?= $hours[0] ?> - <?= $hours[1] ?></td>
                            @foreach ($days as $i => $day)
                                <td class="timetable_data" data-time="<?=$hours[0]?>" data-day="<?=$day?>"></td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
