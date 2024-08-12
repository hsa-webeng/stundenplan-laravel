<?php

namespace App\Http\Controllers;

use App\Models\Dozent;
use App\Models\Studiengang;
use App\Models\Stunde;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
    public function index($mode = 0, $id = null, $semester = null): RedirectResponse|View
    {
        if ($mode === 'my') {
            $mode = 0;
            $id = null;
            $semester = null;
        }

        $mode = (int) $mode;
        $id = $id !== null ? (int) $id : null;
        $semester = $semester !== null ? (int) $semester : null;

        // get all stunden of the kurse of dozent
        if ($mode === 0) {
            if ($id === null) { // when no dozent id is given, use the current dozent
                $dozent = Auth::user()->dozent;
            } else { // when dozent id is given, use the dozent with the given id
                $dozent = Dozent::find($id);
            }

            $stunden = $dozent->load('kurse.stunden')->kurse->pluck('stunden')->collapse();

            // fetch all stunden for the studiengang and semester of each kurs
            $allStunden = Stunde::whereHas('kurs', function ($query) use ($dozent) {
                $query->whereIn('stdg_id', $dozent->kurse->pluck('stdg_id'))
                    ->whereIn('semester', $dozent->kurse->pluck('semester'));
            })->with(['kurs.dozent', 'kurs.studiengang'])->get();

            // detect conflicts and append them to stunden
            $conflictingStunden = collect();
            foreach ($stunden as $stunde) {
                $conflicts = $allStunden->filter(function ($s) use ($stunde) {
                    return $s->id !== $stunde->id &&
                        $s->wochentag === $stunde->wochentag &&
                        $s->kurs->stdg_id === $stunde->kurs->stdg_id &&
                        $s->kurs->semester === $stunde->kurs->semester &&
                        (
                            ($s->block_start < $stunde->block_end && $s->block_end > $stunde->block_start) ||
                            ($stunde->block_start < $s->block_end && $stunde->block_end > $s->block_start)
                        );
                });
                $conflictingStunden = $conflictingStunden->concat($conflicts);
            }

            // merge original stunden with conflicting stunden
            $stunden = $stunden->concat($conflictingStunden);

            $stdg = null;
        }
        // get all kurse of the selected studiengang
        else if ($mode === 1) {
            $dozent = null;
            $stdg = ['kürzel' => Studiengang::find($id)->stdg_kürzel, 'semester' => $semester];

            // get all stunden of all kurses of the selected studiengang
            $stunden = Stunde::whereHas('kurs', function ($query) use ($semester, $id) {
                $query->where('semester', $semester)
                    ->where('stdg_id', $id);
            })->with('kurs')->get();
        }
        else {
            // redirect to users.index
            return redirect()->route('users.index')->with('error', 'Ungültiger Modus.');
        }

        // format block_start and block_end to HH:mm
        foreach ($stunden as $stunde) {
            $stunde->block_start = date('H:i', strtotime($stunde->block_start));
            $stunde->block_end = date('H:i', strtotime($stunde->block_end));
        }

        $days = $this->days;
        $hours_pairs = array_map(null, $this->start_times, $this->end_times);
        return view('stundenplan.plan_doz', compact('days', 'hours_pairs', 'stunden', 'dozent', 'stdg'));
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

        // TODO: check if start_time & end_time are the correct for the sws of the kurs

        // TODO: check if stunden overlap

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

        // check if dozent has any stunden -> set plan_abgegeben to 0 -> else set to null
        $dozent = Auth::user()->dozent;
        $stundenCount = $dozent->kurse->pluck('stunden')->collapse()->count();

        if ($dozent->plan_abgegeben !== 1) {
            if ($stundenCount > 0) {
                $dozent->plan_abgegeben = 0;
            } else {
                $dozent->plan_abgegeben = null;
            }
            $dozent->save();
        }

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
    public function edit(): View|RedirectResponse
    {
        // get all stunden of the kurse of the current dozent
        $dozent = Auth::user()->dozent;

        // check if dozent has plan_abgegeben
        if ($dozent->plan_abgegeben === 1) {
            return redirect()->route('stundenplan.my')->with('error', 'Der Stundenplan wurde bereits abgegeben und kann nicht mehr bearbeitet werden.');
        }

        $stunden = $dozent->load('kurse.stunden')->kurse->pluck('stunden')->collapse();

        // format block_start and block_end to HH:mm
        foreach ($stunden as $stunde) {
            $stunde->block_start = date('H:i', strtotime($stunde->block_start));
            $stunde->block_end = date('H:i', strtotime($stunde->block_end));
        }

        $days = $this->days;
        $hours_pairs = array_map(null, $this->start_times, $this->end_times);
        return view('stundenplan.plan_edit', compact('days', 'hours_pairs', 'stunden'));
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

    public function submitTimetable(int $mode): RedirectResponse
    {
        // mode: 1 to submit, 0 to unsubmit

        if ($mode !== 0 && $mode !== 1) {
            return redirect()->route('stundenplan.my')->with('error', 'Fehler beim Abgeben des Stundenplans.');
        }

        $dozent = Auth::user()->dozent;

        if ($mode === 1) {
            // check if each kurs of the dozent has at least one stunde
            foreach ($dozent->kurse as $kurs) {
                if ($kurs->stunden->count() === 0) {
                    return redirect()->route('stundenplan.my')->with('error', 'Der Stundenplan kann nicht abgegeben werden, da nicht für jeden Kurs mindestens eine Stunde eingetragen wurde.');
                }
            }

            $dozent->plan_abgegeben = true;
            $message = 'Stundenplan erfolgreich abgegeben.';
        } else {
            $dozent->plan_abgegeben = false;
            $message = 'Stundenplan erfolgreich zurückgezogen.';
        }
        $dozent->save();

        return redirect()->route('stundenplan.my')->with('success', $message);
    }
}
