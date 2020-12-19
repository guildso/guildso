<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function index(Type $var = null)
    {
        return view('tasks.index');
    }
}
