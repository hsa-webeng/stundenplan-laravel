<?php

namespace App\Http\Controllers;

use App\Models\Studiengang;
use Illuminate\View\View;
use Illuminate\Http\Request;

class StudiengangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $studiengaenge = Studiengang::all()->sortBy('stdg_name');
        return view('studiengänge.list_stdgs', compact('studiengaenge'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('studiengänge.manage_stdg');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'stdg_name' => ['required', 'string', 'max:255', 'unique:studiengänge,stdg_name'],
            'stdg_short' => ['required', 'string', 'max:5', 'unique:studiengänge,stdg_kürzel'],
        ]);

        Studiengang::create([
            'stdg_name' => $request->stdg_name,
            'stdg_kürzel' => $request->stdg_short,
        ]);

        return redirect(route('studiengänge.index'))->with('success', 'Der Studiengang "' . $request->stdg_name . '" wurde erfolgreich hinzugefügt.');
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
        $studiengang = Studiengang::findOrFail($id);
        return view('studiengänge.manage_stdg', compact('studiengang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'stdg_name' => ['required', 'string', 'max:255', 'unique:studiengänge,stdg_name,' . $id],
            'stdg_short' => ['required', 'string', 'max:5', 'unique:studiengänge,stdg_kürzel,' . $id],
        ]);

        $studiengang = Studiengang::findOrFail($id);

        $wasChanged = false;

        if ($studiengang->stdg_name !== $request->stdg_name) {
            $studiengang->stdg_name = $request->stdg_name;
            $wasChanged = true;
        }
        if ($studiengang->stdg_kürzel !== $request->stdg_short) {
            $studiengang->stdg_kürzel = $request->stdg_short;
            $wasChanged = true;
        }

        if (!$wasChanged) {
            return redirect(route('studiengänge.index'))->with('info', 'Es wurden keine Änderungen am Studiengang "' . $request->stdg_short . '" vorgenommen.');
        }

        $studiengang->save();
        return redirect(route('studiengänge.index'))->with('success', 'Der Studiengang "' . $request->stdg_short . '" wurde erfolgreich bearbeitet.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $studiengang = Studiengang::findOrFail($id);
            $studiengang->delete();
            return back()->with('success', 'Der Studiengang wurde erfolgreich gelöscht.');
        }
        catch (\Exception $e) {
            return back()->with('error', 'Es ist ein Fehler aufgetreten: ' . $e->getMessage());
        }
    }
}
