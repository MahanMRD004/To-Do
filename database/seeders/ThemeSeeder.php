<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $themes = [
            ['name'=>'myday','img'=>'bg_myday.jpg'],
            ['name'=>'all','img'=>'bg_all.jpg'],
            ['name'=>'complete','img'=>'bg_complete.jpg'],
            ['name'=>'orange','img'=>'bg_orange.jpg'],
            ['name'=>'blue','img'=>'bg_blue.jpg'],
            ['name'=>'purple','img'=>'bg_purple.jpg'],
            ['name'=>'darkblue','img'=>'bg_darkblue.jpg'],
            ['name'=>'gaming','img'=>'bg_gaming.jpg']
        ];

        DB::table('themes')->insert($themes);
    }
}
