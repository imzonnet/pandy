<?php
/**
 * Created by PhpStorm.
 * User: vnzac
 * Date: 2/29/2016
 * Time: 9:39 AM
 */

namespace App\Libs;


use Illuminate\Support\Facades\Auth;

class BankHelper
{
    /**
     * Calculates the payment for a loan based on constant payments and a constant interest rate.
     *
     * @param $interest
     * @param $months
     * @param $loan
     * @return float
     */
    public function pmt($interest, $months, $loan) {
        $interest = $interest / 1200;
        $amount = $interest * -$loan * pow((1 + $interest), $months) / (1 - pow((1 + $interest), $months));
        return $amount;
    }

    /**
     * Calc monthly payment
     * @param $interest_rate
     * @param $loan_term
     * @param $loan_amount
     * @return float
     */
    public function monthlyPayment($interest_rate, $loan_term, $loan_amount) {
        return $this->pmt($interest_rate, $loan_term * 12, $loan_amount);
    }

    /**
     * Get Number Of Payment
     *
     * @param $interestRate
     * @param $newMonthlyPayment
     * @param $loanAmount
     * @return float
     */
    public function numberOfPayments($interestRate, $newMonthlyPayment, $loanAmount)
    {
        return ceil($this->NPer($interestRate, $newMonthlyPayment, -$loanAmount));
    }
    
       /**
     * Calc Interest Payment
     *
     * @param $balance
     * @param $interest_rate
     * @return float
     */
    public function interest($balance, $interest_rate) {
        return $interest_rate / 1200 * $balance;
    }

    /**
     * Get Principal
     *
     * @param $monthly_payment
     * @param $interest_payment
     * @return mixed
     */
    public function principal($monthly_payment, $interest_payment) {
        return $monthly_payment - $interest_payment;
    }

    /**
     * The Microsoft Excel NPER function returns the number of periods
     * for an investment based on an interest rate and a constant payment schedule.
     *
     * @param $rate
     * @param $payment
     * @param $present
     * @param int $future
     * @param int $type
     * @return float
     *
     */
    function NPer($rate, $payment, $present, $future = 0, $type = 0) {
        $rate = $rate / 1200;
        $num = $payment * (1 + $rate * $type) - $future * $rate;
        $den = ($present * $rate + $payment * (1 + $rate * $type));
        return log10($num / $den) / log10(1 + $rate);
    }

}