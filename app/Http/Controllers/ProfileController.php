<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Dozent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Controller for the actions a user can perform on their profile.
 * (Except for deletion, admins only)
 */
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request, int $id, bool $isUser = true): RedirectResponse
    {
        DB::beginTransaction();

        try {
            if ($isUser) {
                $user = User::findOrFail($id);

                // check if user is last admin
                if ($user->admin && User::where('admin', true)->count() === 1) {
                    throw new \Exception('Der letzte Administrator kann nicht gelöscht werden.');
                }

                // handle current user deletion
                if (Auth::id() === $user->id) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                }

                if ($user->dozent) {
                    $message = 'Benutzer & Dozent erfolgreich gelöscht.';
                }
                else {
                    $message = 'Benutzer erfolgreich gelöscht.';
                }

                $user->delete(); // cascade delete associated tables (e.g. dozenten, ...)
            }
            else {
                $dozent = Dozent::findOrFail($id);
                $dozent->delete(); // cascade delete associated tables (e.g. kurse, ...)
                $message = 'Dozent erfolgreich gelöscht.';
            }

            DB::commit();
            return redirect()->route('users.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Es ist ein Fehler aufgetreten: ' . $e->getMessage());
        }
    }
}
