<?php

use Illuminate\Database\Seeder;

class AvatarZeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('avatars')->insert(
        ['rank' => 0, 'min_hours' => -1, 'max_hours' => 9999]
      );
    }
}
