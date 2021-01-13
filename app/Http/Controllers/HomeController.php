<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //returns to login page if  not authenticated
    public function __construct()
    {
        $this->middleware('auth');
    }

    //show the dashboard
    public function home(){
        return view('dashboard');
    }
}
