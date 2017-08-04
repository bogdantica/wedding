<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('guests')->delete();

        $users = [
            'tikagnus',
            'puiu',
            'andreea'
        ];

        foreach ($users as $user) {

            \App\User::firstOrCreate([
                'name' => 'User',
                'email' => $user . '@gmail.com',
                'password' => bcrypt('anaaremere')
            ]);
        }

        \Cache::forget('guestListCached');

    }
}
