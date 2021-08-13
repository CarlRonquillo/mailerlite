<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FakeKeySeeder extends Seeder
{

    public function run()
    {
        DB::table('keys')->insert([
            'key' => Str::random(20),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
