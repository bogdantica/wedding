<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Readers\LaravelExcelReader;
use Yajra\Datatables\Facades\Datatables;

class GuestsController extends Controller
{

    protected function parseTable($table)
    {
        $string = strtolower($table);

        return trim(str_replace('masa', '', $table));
    }

    public function landing(Request $request, $search = null)
    {
        if (is_null($search)) {
            return view('guests.landing');
        }

        $cache = '-';

        $query = Guest::select('name', 'table')->orderBy('name');


        if ($request->has('search.value')) {

            $string = $this->parseTable($request->search['value']);

            if (is_numeric($string)) {
                $query->where('table', $string);

            } else {

                $string = strtolower($string);

                $query->where(\DB::raw('LOWER(name)'), 'LIKE', '%' . $string . '%');
            }

            $cache .= $string;

        }

        $guests = $this->getByQuery($query, $cache);



        return Datatables::collection($guests)->make(true);
    }

    protected function getByQuery($query, $suffix)
    {
//        return \Cache::rememberForever('guestListCached' . $suffix, function () use ($query) {
            return $query->get()->map(function ($guest) {
                return [
                    'name' => $guest->name . ' - Masa ' . $guest->table,
                    'table' => 'Masa ' . $guest->table
                ];
            });
//        });


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

    public function importFile(Request $request)
    {

        $this->validate($request, [
//            'importFile' => 'required|file|mimes:xlsx,xls,csv',
//            'eraseOldList' => 'nullable'
        ]);

        $file = $request->file('importFile')->getPathname();

        $guestList = collect();

//       dump($file);
        \Excel::load($file, function (LaravelExcelReader $reader) use ($guestList) {

            $collection = $reader->get();

//		dd($collection);

            $collection->each(function ($row) use ($guestList) {
		//dd($row,$row->masa,$row['masa']);
                $table = strtolower($row->masa ?$row->masa :  $row['masa']);

                $table = str_replace('masa', '', $table);
                $table = trim($table);
		if(!empty($row->invitat)){

                $guestList->push([
                    'name' => ucwords($row->invitat ? $row->invitat: $row['invitat']),
                    'table' => $table
                ]);
		}

            });
        });
	\Cache::forget('importFileGuests');
        \Cache::rememberForever('importFileGuests', function () use ($guestList) {
            return $guestList;
        });


        return redirect('/validate');


    }


    public function validateList(Request $request)
    {

        $guestList = \Cache::get('importFileGuests');

        $import = $request->get('import', false);

        if ($import) {
            if ($request->get('import') == 'replace') {
                \DB::table('guests')->delete();
            }

            $guestList = \Cache::get('importFileGuests');

//            \Cache::forget('guestListCached');
            \Cache::forget('importFileGuests');

	//dd($guestList->all(),$guestList->toArray());
            \DB::table('guests')->insert($guestList->all());

            return redirect(route('guests'))->withSuccess('Success');

        }

        return view('guests.validateList', ['guests' => $guestList]);
    }

}
