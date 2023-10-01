<?php

namespace App\Http\Controllers;

use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()                       // Проект начинается здесь.
    {
        $this->middleware('auth');            // Если пользователь НЕ залогинен, то направляет на страницу login.
    }                                                   // Если пользователь залогинен, то выполняется функция index().

                                                        // Функция index() является действием, которое будет выполнено
    public function index()                             // при обращении к маршруту, связанному с данным контроллером.
    {
        $access = '0';

        if (Auth::check())                              // Если пользователь залогинен, то
        {
            $user = Auth::user();                       // переменной $user присваивается текущий пользователь.

            $userAccess = $user->access()->first();

            if ($userAccess->admin)
            {
                $access = '1';
            }
        }

        return view('home.index', compact('access'));
    }
}
