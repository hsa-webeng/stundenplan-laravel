<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StundenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $days = ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"];
        $hours_pairs = [
            ["08:15", "09:45"],
            ["10:00", "11:30"],
            ["11:45", "13:15"],
            ["14:15" , "15:45"],
            ["16:00", "17:30"],
            ["17:45", "19:15"],
            ["19:30", "21:00"]
        ];

        return view('stundenplan.plan_doz', compact('days', 'hours_pairs'));
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
    public function destroy(string $id)
    {
        //
    }
}
