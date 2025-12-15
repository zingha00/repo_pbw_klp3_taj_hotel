<?php

namespace App\Http\Controllers;

use App\Models\Room;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $rooms = Room::where('available', true)->get();
        return view('home', compact('rooms'));
    }
    public function aboout()
    {
        return view ('about');
    }
    public function contact()
    {
        return view('contact');
    }
    
}