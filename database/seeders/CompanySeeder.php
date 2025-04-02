<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
            'name' => 'Cris Info',
            'address' => 'CDE',
            'phone' => '0983668960',
            'RUC' => '6560887-9',
        ]);
    }
}
