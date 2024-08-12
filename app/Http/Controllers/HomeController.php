<?php

namespace App\Http\Controllers;

use App\Models\Studiengang;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     *
     */
    public function index(): View
    {
        $adminCount = User::where('admin', true)->count();
        return view('welcome', ['showRegister' => $adminCount === 0]);
    }

    public function dashboard(): View
    {
        $studiengaenge = Studiengang::all();

        return view('dashboard', ['studiengaenge' => $studiengaenge]);
    }
}
