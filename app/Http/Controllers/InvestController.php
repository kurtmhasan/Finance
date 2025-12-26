<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvestController extends Controller
{
    public function index()
    {
        return view('invest-page');
    }
}
