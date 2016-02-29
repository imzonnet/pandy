<?php

use Illuminate\Database\Seeder;

class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = ['Bank 1', 'Bank 2', 'Bank 3', 'Bank 4', 'Bank 5'];
        $interestRate = [4.4, 5.3, 5.0, 5.4, 5.2, 5.5];
        $comparisonRate = [4.2, 5.0, 4.7, 5.1, 4.8, 5.1];
        $annualFees = [1000, 2000, 3000, 4000, 5000];
        $monthlyFees = [100, 200, 300, 400, 500];
        for( $i = 0; $i < count($list); $i++ ) {
            \App\Bank::create([
                'name' => $list[$i],
                'interest_rate' => $interestRate[array_rand($interestRate)],
                'comparison_rate' => $comparisonRate[array_rand($comparisonRate)],
                'annual_fees' => $annualFees[array_rand($annualFees)],
                'monthly_fees' => $monthlyFees[array_rand($monthlyFees)],
                'special' => 'N/A',
            ]);
        }
    }
}
