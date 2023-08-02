<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth

class HomeController extends Controller
{
    public function index()
    {
        // Check if the user is authenticated
        if(Auth::check()) {
            // If the user is authenticated, return the logged in view
            return view('panda');
        } else {
            // If the user is not authenticated, return the guest view
            return view('welcome');
        }
    }
}