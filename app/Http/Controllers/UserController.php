<?php

namespace App\Http\Controllers;

use App\Models\Dozent;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;

/**
 * Handles all user & Dozenten related actions
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::all();
        $dozenten = Dozent::all();
        // get the view and pass the users and dozenten arrays
        return view('users.index', compact('users', 'dozenten'));
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
