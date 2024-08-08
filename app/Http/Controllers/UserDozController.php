<?php

namespace App\Http\Controllers;

use App\Models\Dozent;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

/**
 * Handles all user & Dozenten related actions
 * (e.g. listing, creating & updating; deleting is handled by the ProfileController)
 */
class UserDozController extends Controller
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
        return view('users.list_users', ['users_dozenten' => $users_dozenten]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(int $mode, ?int $id = null): View
    {
        return view('users.add_user', ['mode' => $mode, 'id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $mode, ?int $id = null)
    {
        $message = 'Der ';

        // adding a user (1) or both (0)
        if ($mode === 1 || $mode === 0) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => ['required', 'boolean'],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'admin' => $request->role,
            ]);

            event(new Registered($user));

            // when adding a user & a dozent id is given, assign the user id to the dozent
            // we are creating a user for an existing dozent
            if ($mode === 1 && $id !== null) {
                // id is the dozent id -> fetch the dozent and assign the user id
                $dozent = Dozent::find($id);
                $dozent->user_id = $user->id;
                $dozent->save();
            }

            $message .= 'Benutzer "' . $request->name . '"';
        }

        // adding a dozent (2) or both (0)
        if ($mode === 2 || $mode === 0) {
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
            ]);

            $dozent = Dozent::create([
                'dozent_vorname' => $request->first_name,
                'dozent_nachname' => $request->last_name,
                'plan_abgegeben' => null,
            ]);

            // when adding both, assign the newly created user id to the dozent
            if ($mode === 0) {
                $dozent->user_id = $user->id;
            }

            // when adding a dozent & a user id is given, assign the user id to the dozent
            // we are creating a dozent for an existing user
            if ($mode === 2 && $id !== null) {
                $dozent->user_id = $id;
            }

            $dozent->save();

            if ($message !== 'Der ') {
                $message .= ' & ';
            }
            $message .= 'Dozent "' . $request->first_name . ' ' . $request->last_name . '"';
        }

        $message .= ' wurde erfolgreich hinzugefügt.';

        if ($message !== 'Der  wurde erfolgreich hinzugefügt.') {
            return redirect(route('users.index'))->with('success', $message);
        } else {
            return redirect(route('users.index'));
        }
    }

    /**
     * Display a specific resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource (frontend)
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage (backend)
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
