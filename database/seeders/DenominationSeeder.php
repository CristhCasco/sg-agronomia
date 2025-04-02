<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Denomination;

class DenominationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Denomination::create([
            'type' => 'OTRO',
            'value' => '0',
            'image' => 'https://dummyimage.com/200x150/5c5756/fff'
        ]);
    }
}
