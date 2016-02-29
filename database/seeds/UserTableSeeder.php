<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'John Nguyen',
            'email' => 'vnzacky39@gmail.com',
            'password' => bcrypt('123456'),
            'phone' => '12346789',
            'group' => 1,
            'loan_amount' => 300900,
            'interest_rate' => 5.5,
            'switching_costs' => 2000,
            'ongoing_costs' => 1500,
            'loan_term' => 30,
        ]);
        \App\User::create([
            'name' => 'Jayden Vecchio',
            'email' => 'jaydenvecchio39@gmail.com',
            'password' => bcrypt('123456'),
            'phone' => '12346788',
            'group' => 1,
            'loan_amount' => 300900,
            'interest_rate' => 5.5,
            'switching_costs' => 2000,
            'ongoing_costs' => 1500,
            'loan_term' => 30,
        ]);
    }
}
