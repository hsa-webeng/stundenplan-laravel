<?php

namespace App\Http\Controllers;

use App\Models\Dozent;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Log;

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
        // get all users sorted by their dozenten nachname
        $users = User::orderBy('dozenten.dozent_nachname', 'asc')
            ->leftJoin('dozenten', 'users.id', '=', 'dozenten.user_id')
            ->select('users.id as u_id', 'dozenten.id as d_id', 'users.admin', 'dozenten.dozent_vorname', 'dozenten.dozent_nachname', 'dozenten.plan_abgegeben', 'users.name', 'users.email')
            ->get();

        $dozenten = Dozent::orderBy('dozent_nachname', 'asc')
            ->select('id as d_id', 'dozent_vorname', 'dozent_nachname', 'plan_abgegeben')
            ->where('user_id', null)
            ->get();

        // merge the users and dozenten arrays (but each dozent has to be inside an empty user array)
        $users_dozenten = $users->toBase()->merge($dozenten);

        // get the view and pass the users and dozenten arrays
        return view('users.index', ['users_dozenten' => $users_dozenten]);
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
