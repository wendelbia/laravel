<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
    	$name = (auth()->user()->name);
    	return view('admin.home.index', compact('name'));
    }
}
