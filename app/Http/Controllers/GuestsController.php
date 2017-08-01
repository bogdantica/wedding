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

        $query = Guest::select('name', 'table')->orderBy('name');


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

    public function list(Request $request)
    {

        if (count($request->all())) {

            $table = strtolower($request->table);
            $table = str_replace('masa', '', $table);
            $table = trim($table);
            $request->merge(['table' => $table]);

            $this->validate($request, [
                'name' => 'string|required',
                'table' => 'numeric|required|between:0,50'
            ]);

            if (!$request->has('id')) {

                $guest = Guest::create([
                    'name' => $request->name,
                    'table' => $request->table
                ]);

            }else{

                $this->validate($request, [
                    'id' => 'required|exists:guests',
                ]);

                $guest = Guest::find($request->id);


            }


            $guest->update($request->only('name', 'table'));
            $guest->table = 'Masa ' . $guest->table;
            return $guest;
        }


        $guests = Guest::orderBy('name')->get();


        return view('guests.list', compact('guests'));

    }


}
