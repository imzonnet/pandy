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
    public function pmt($interest, $months, $loan)
    {
        $interest = $interest / 1200;
        $amount = $interest * -$loan * pow((1 + $interest), $months) / (1 - pow((1 + $interest), $months));
        return $amount;
    }

    /**
     * Calc monthly payment
     *
     * @param $interestRate
     * @param $loanTerm
     * @param $loanAmount
     * @return float
     */
    public function monthlyPayment($interestRate, $loanTerm, $loanAmount)
    {
        return $this->pmt($interestRate, $loanTerm * 12, $loanAmount);
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
     * Get Total Interest Payments
     *
     * @param $interestRate
     * @param $loanTerm
     * @param $loanAmount
     *
     * @return float
     */
    public function totalInterestNoExtraPayments($interestRate, $loanTerm, $loanAmount)
    {
        return -\PHPExcel_Calculation_Financial::CUMIPMT($interestRate / 1200, $loanTerm * 12, $loanAmount, 1, $loanTerm * 12);
    }

    /**
     * @param $interestRate
     * @param $numberOfPayments
     * @param $newMonthlyPayment
     * @param $loanAmount
     * @return float|int
     */
    public function totalInterestExtraPayments($interestRate, $numberOfPayments, $newMonthlyPayment, $loanAmount)
    {
        $total = 0;
        $newLoanAmount = $loanAmount;
        for ($i = 1; $i <= $numberOfPayments; $i++) {
            $interestPayment = $this->interestPayment($interestRate, $newLoanAmount);
            $total += $interestPayment;
            if ($i == $numberOfPayments) {
                $newMonthlyPayment = (1 + $interestRate / 1200) * $newLoanAmount;
            }
            $principalPayment = $this->principalPayment($newMonthlyPayment, $interestPayment);
            $newLoanAmount = $newLoanAmount - $principalPayment;

        }
        return $total;
    }

    /**
     * Get current loan amount of a month
     *
     * @param $currentMonth
     * @param $interestRate
     * @param $newMonthlyPayment
     * @param $loanAmount
     * @return mixed
     */
    public function getLoanAmountOfMonth($currentMonth, $interestRate, $newMonthlyPayment, $loanAmount)
    {
        $newLoanAmount = $loanAmount;
        for ($i = 1; $i < $currentMonth; $i++) {
            $interestPayment = $this->interestPayment($interestRate, $newLoanAmount);
            $principalPayment = $this->principalPayment($newMonthlyPayment, $interestPayment);
            $newLoanAmount = $newLoanAmount - $principalPayment;
        }
        return $newLoanAmount;
    }

    /**
     * Calc Interest Payment
     *
     * @param $balance
     * @param $interestRate
     * @return float
     */
    public function interestPayment($interestRate, $balance)
    {
        return $interestRate / 1200 * $balance;
    }

    /**
     * Get Principal
     *
     * @param $monthlyPayment
     * @param $interestPayment
     * @return mixed
     */
    public function principalPayment($monthlyPayment, $interestPayment)
    {
        return $monthlyPayment - $interestPayment;
    }

    /**
     * Get Current Monthly Payment
     *
     * @param $currentMonth
     * @param $numberOfPayments
     * @param $monthlyPayment
     * @param $interestRate
     * @param $loanAmount
     * @return int
     */
    public function currentMonthlyPayment($currentMonth, $numberOfPayments, $monthlyPayment, $interestRate, $loanAmount)
    {
        $currentMonthlyPayment = 0;
        if( $currentMonth < $numberOfPayments ) {
            $currentMonthlyPayment = $monthlyPayment;
        } else if( $currentMonth == $numberOfPayments ) {
            $currentMonthlyPayment = ( 1 + $interestRate / 1200 ) * $loanAmount;
        }
        return $currentMonthlyPayment;
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
    function NPer($rate, $payment, $present, $future = 0, $type = 0)
    {
        $rate = $rate / 1200;
        $num = $payment * (1 + $rate * $type) - $future * $rate;
        $den = ($present * $rate + $payment * (1 + $rate * $type));
        return log10($num / $den) / log10(1 + $rate);
    }

}