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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('TRUNCATE TABLE users');
        DB::statement('TRUNCATE TABLE password_resets');
        DB::statement('TRUNCATE TABLE banks');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->call(UserTableSeeder::class);
        $this->call(BankTableSeeder::class);
    }
}
