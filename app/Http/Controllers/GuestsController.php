<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestsController extends Controller
{
    public function landing()
    {

        return view('guests.landing');
    }

    public function search(Request $request)
    {

    }

}
