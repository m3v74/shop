<?php

namespace App\Http\Controllers;

use App\Models\User;

class AccessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
        return view('access.index', compact('users'));
    }
}
