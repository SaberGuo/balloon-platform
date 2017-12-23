<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $user = $req->user()->load('roles.permissions');
        View::share('user', $user);
        return view('home');
    }
}
