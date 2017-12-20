<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $req)
    {
        $user = $req->user()->load('roles.permissions');
        View::share('user', $user);
        return view('home');
    }
}
