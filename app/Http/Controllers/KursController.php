<?php

namespace App\Http\Controllers;

use App\Models\Dozent;
use App\Models\Kurs;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KursController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $dozenten = Dozent::all()->sortBy('dozent_nachname');
        $kurse = Kurs::all()->sortBy('kurs_name');
        return view('kurse.list_kurse', compact('kurse', 'dozenten'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $kurs = Kurs::findorFail($id);
            $kurs->delete();
            return redirect()->route('kurse.index')->with('success', 'Kurs erfolgreich gelöscht.');
        }
        catch (\Exception $e) {
            return redirect()->route('kurse.index')->with('error', 'Es ist ein Fehler aufgetreten: ' . $e->getMessage());
        }
    }
}
