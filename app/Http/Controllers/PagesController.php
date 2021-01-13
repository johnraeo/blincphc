<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;

use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
  public function view(Request $request){
    return view('auth.login');
  }
}