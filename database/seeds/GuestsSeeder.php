<?php

use Illuminate\Database\Seeder;

class GuestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 200; $i++)
            \DB::table('guests')->insert([
                'name' => $faker->name,
                'table' => rand(1, 12)
            ]);
    }
}
