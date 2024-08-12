<?php

namespace App\Http\Controllers;

use App\Models\Stunde;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StundenController extends Controller
{
    private array $days = ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"];

    private array $start_times = ["08:15", "10:00", "11:45", "14:15", "16:00", "17:45", "19:30"];
    private array $end_times = ["09:45", "11:30", "13:15", "15:45", "17:30", "19:15", "21:00"];

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // get all stunden of the kurse of the current dozent
        $dozent = Auth::user()->dozent;
        $stunden = $dozent->load('kurse.stunden')->kurse->pluck('stunden')->collapse();

        // format block_start and block_end to HH:mm
        foreach ($stunden as $stunde) {
            $stunde->block_start = date('H:i', strtotime($stunde->block_start));
            $stunde->block_end = date('H:i', strtotime($stunde->block_end));
        }

        $days = $this->days;
        $hours_pairs = array_map(null, $this->start_times, $this->end_times);
        return view('stundenplan.plan_doz', compact('days', 'hours_pairs', 'stunden'));
    }

    public function parseTimetableJson(Request $request): JsonResponse
    {
        $request->validate([
            'timetable_state' => 'required|json'
        ]);

        $timetableState = json_decode($request->input('timetable_state'), true);

        $rules = [
            '*.kurs_id' => 'required|integer|exists:kurse,id',
            '*.day' => 'required|integer|min:0|max:5',
            '*.start_time' => [
                'required',
                'string',
                'date_format:H:i',
                Rule::in($this->start_times)
            ],
            '*.end_time' => [
                'required',
                'string',
                'date_format:H:i',
                'after:*.start_time',
                Rule::in($this->end_times)
            ],
            '*.stunde_id' => 'nullable|integer|exists:stunden,id'
        ];

        $validator = Validator::make($timetableState, $rules);

        if ($validator->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => 'Fehler beim Speichern des Stundenplans: ' . $validator->errors(),
            ], 422);
        }

        $changesDetected = false;

        $kurse_ids_data = [];

        try {
            foreach ($timetableState as $entry) {
                // append kurs_id to array
                $kurse_ids_data[] = $entry['kurs_id'];

                if (isset($entry['stunde_id'])) {
                    // update when stunde_id is set
                    $stunde = Stunde::find($entry['stunde_id']);
                    if ($stunde) {
                        $changesDetected = $this->update($stunde, $entry) || $changesDetected;
                    }
                } else { // create when stunde_id is not set
                    // check if kurs already has a stunde
                    $stunde = Stunde::where('kurs_id', $entry['kurs_id'])->first();
                    if ($stunde) {
                        // stunde already exists, update it
                        $changesDetected = $this->update($stunde, $entry) || $changesDetected;
                    } else {
                        // stunde does not exist, create it
                        $this->store($entry);
                        $changesDetected = true;
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Fehler beim Speichern des Stundenplans: ' . $e->getMessage()
            ], 500);
        }

        // get all kurse ids of the current dozent
        $kurse_ids_db = Auth::user()->dozent->kurse->pluck('id')->toArray();

        // compare the two arrays & delete all stunden for kurse that are not in the data
        $kurse_ids_to_delete = array_diff($kurse_ids_db, $kurse_ids_data);
        if (count($kurse_ids_to_delete) > 0) {
            Stunde::whereIn('kurs_id', $kurse_ids_to_delete)->delete();
            $changesDetected = true;
        }

        // TODO: set dozent plan_abgegeben to 0

        if ($changesDetected) {
            return response()->json([
                'type' => 'success',
                'message' => 'Stundenplan erfolgreich gespeichert.'
            ]);
        }
        else {
            return response()->json([
                'type' => 'info',
                'message' => 'Keine Änderungen am Stundenplan festgestellt.'
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(array $entry)
    {
        Stunde::create([
            'kurs_id' => $entry['kurs_id'],
            'wochentag' => $entry['day'],
            'block_start' => $entry['start_time'],
            'block_end' => $entry['end_time']
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Stunde $stunde, array $entry): bool
    {
        $wasChanged = false;
        if ((int)$stunde->kurs_id !== (int)$entry['kurs_id']) {
            $stunde->kurs_id = $entry['kurs_id'];
            $wasChanged = true;
        }
        if ((int)$stunde->wochentag !== (int)$entry['day']) {
            $stunde->wochentag = $entry['day'];
            $wasChanged = true;
        }
        if (strtotime($stunde->block_start) !== strtotime($entry['start_time'])) {
            $stunde->block_start = $entry['start_time'];
            $wasChanged = true;
        }
        if (strtotime($stunde->block_end) !== strtotime($entry['end_time'])) {
            $stunde->block_end = $entry['end_time'];
            $wasChanged = true;
        }
        if ($wasChanged) {
            $stunde->save();
        }
        return $wasChanged;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
