<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::all();
        return response()->json(['applications' => $applications], 200);
    }
    
    public function test()
    {
        $applications = Application::all();
        return response()->json(['applications' => 'applications'], 200);
    }
}
