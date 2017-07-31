<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

class GuestsController extends Controller
{
    public function landing(Request $request, $search = null)
    {
        if (is_null($search)) {
            return view('guests.landing');
        }

        $query = Guest::select('name', 'table');


        if ($request->has('search.value')) {

            $string = $request->search['value'];

            $string = strtolower($string);

            $string = trim(str_replace('masa', '', $string));


            if (is_numeric($string)) {
                $query->where('table', $string);

            } else {
                $query->where(\DB::raw('LOWER(name)'), 'LIKE', '%' . $string . '%');
            }

        }


        $guests = $query->get()->map(function ($guest) {
            return [
                'name' => $guest->name,
                'table' => 'Masa ' . $guest->table
            ];
        });


        return Datatables::collection($guests)->make(true);
    }

    public function search(Request $request)
    {
        return Datatables::eloquent(Guest::query())->make(true);
    }

}
