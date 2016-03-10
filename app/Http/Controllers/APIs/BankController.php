<?php namespace App\Http\Controllers\APIs;

use App\Libs\BankHelper;
use App\Repositories\BankRepository;
use App\Transforms\BankTransformer;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Auth;

class BankController extends APIController {

    protected $bank;
    protected $bankHelper;
    protected $bankTransformer;

    public function __construct(BankRepository $bank, BankTransformer $bankTransformer, BankHelper $bankHelper)
    {
        $this->bank = $bank;
        $this->bankHelper = $bankHelper;
        $this->bankTransformer = $bankTransformer;
    }

    /**
     * Get list banks
     * @return \Illuminate\Http\JsonResponse
     */
    public function listAll()
    {
        try {
            $banks = $this->bank->all();

            if( !$banks ) {
                return $this->respondNotFound("Don't have any banks");
            }

            return $this->respondWithSuccess(
                $this->bankTransformer->transformCollection($banks)
            );
        } catch (Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Get info of a bank
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBank($id)
    {
        try {
            $bank = $this->bank->find($id);

            return $this->respondWithSuccess(
                $this->bankTransformer->transform($bank->toArray())
            );
        } catch (NotFoundHttpException $e) {
            return $this->respondNotFound("The bank not found");
        }
    }

    /**
     * How much can save?
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBankSave(Request $request)
    {
        try {
            /** Set Value */
            $user = Auth::guard('api')->user();
            $loanAmount = (float)$request->get('loan_amount', $user->loan_amount);
            $interestRate = $request->get('interest_rate', $user->interest_rate);
            $loanTerm = $request->get('loan_term', $user->loan_term);
            $switchingCosts = $request->get('switching_costs', 0);
            $ongoingCosts = $request->get('ongoing_costs', 0);
            $extraPayment = (float)$request->get('extra_payment', 0);
            //update loan amount
            $oldLoanAmount = $loanAmount;
            $loanAmount += $switchingCosts + $ongoingCosts;
            if( (int)$loanAmount < 1 || (int)$loanTerm < 1  ) {
                return $this->setStatusCode(401)->respondWithError("Please update loan amount and loan term");
            }

            /** Calc */
            $oldMonthlyPayment = $this->bankHelper->monthlyPayment($interestRate, $loanTerm, $loanAmount);
            $newMonthlyPayment = (float)$oldMonthlyPayment + $extraPayment;
            $numberOfPayments = $this->bankHelper->numberOfPayments($interestRate, $newMonthlyPayment, $loanAmount);
            //total interest no extra payment
            $totalInterestNoExtraPayments = $this->bankHelper->totalInterestNoExtraPayments($interestRate, $loanTerm, $loanAmount);
            //total interest extra payment
            $totalInterestExtraPayments = $this->bankHelper->totalInterestExtraPayments($interestRate, $numberOfPayments, $newMonthlyPayment, $loanAmount);

            $refiAndSave = ($totalInterestNoExtraPayments - $totalInterestExtraPayments) / $loanTerm / 12;
            $yearSaved = $loanTerm - $numberOfPayments / 12;
            return $this->respondWithSuccess([
                'old_monthly_payment' => round($oldMonthlyPayment, 2),
                'new_monthly_payment' => round($newMonthlyPayment, 2),
                'number_of_payments' => $numberOfPayments,
                'total_interest_no_extra_payments' => round($totalInterestNoExtraPayments, 2),
                'total_interest_extra_payments' => round($totalInterestExtraPayments, 2),
                'refi_and_Save' => round($refiAndSave, 2),
                'year_saved' => round($yearSaved, 2),
                'ongoing_costs' => round($ongoingCosts, 2),
                'switching_costs' => round($switchingCosts, 2),
                'loan_amount' => round($oldLoanAmount, 2),
                'loan_term' => (int)$loanTerm,
                'interest_rate' => round($interestRate, 2),
                'extra_payment' => round($extraPayment, 2),
                'loan_save' => round(abs($totalInterestNoExtraPayments - $totalInterestExtraPayments) / $numberOfPayments, 2)
            ]);

        } catch(Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function loanCalculator(Request $request)
    {
        try {
            /** Set Value */
            $user = Auth::guard('api')->user();
            $loanAmount = $request->get('loan_amount', $user->loan_amount);
            $interestRate = $request->get('interest_rate', $user->interest_rate);
            $loanTerm = $request->get('loan_term', $user->loan_term);
            $extraPayment = (float)$request->get('extra_payment', 0);
            $currentMonth = (float)$request->get('current_month_payment', 1);
            $switchingCosts = $request->get('switching_costs', 0);
            $ongoingCosts = $request->get('ongoing_costs', 0);
            //update loan amount
            $oldLoanAmount = $loanAmount;
            $loanAmount += $switchingCosts + $ongoingCosts;

            /** Calc */
            $oldMonthlyPayment = $this->bankHelper->monthlyPayment($interestRate, $loanTerm, $loanAmount);
            $newMonthlyPayment = (float)$oldMonthlyPayment + $extraPayment;
            $numberOfPayments = $this->bankHelper->numberOfPayments($interestRate, $newMonthlyPayment, $loanAmount);

            /** Get new loan amount of a month before */
            $newLoanAmount = $this->bankHelper->getLoanAmountOfMonth($currentMonth, $interestRate, $newMonthlyPayment, $loanAmount);

            /** Get interest payment and principal payment of current month */
            $interestPayment = $this->bankHelper->interestPayment($interestRate, $newLoanAmount);
            $principalPayment = $this->bankHelper->principalPayment($newMonthlyPayment, $interestPayment);

            /** Get Current Monthly Payment */
            $currentMonthlyPayment = $this->bankHelper->currentMonthlyPayment($currentMonth, $numberOfPayments, $newMonthlyPayment, $interestRate, $newLoanAmount);

            return $this->respondWithSuccess([
                'loan_amount' => round($oldLoanAmount, 2),
                'switching_costs' => round($switchingCosts, 2),
                'ongoing_costs' => round($ongoingCosts, 2),
                'loan_term' => round($loanTerm, 2),
                'current_month_payment' => (int)$currentMonth,
                'interest_rate' => round($interestRate, 2),
                'interest_payment' => round($interestPayment, 2),
                'principal_payment' => round($principalPayment, 2),
                'monthly_payment' => round($currentMonthlyPayment, 2),
                'extra_payment' => $extraPayment
            ]);
        } catch(Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

}