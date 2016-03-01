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

            return $this->respond(
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

            return $this->respond(
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
            $loanAmount = $request->get('loan_amount', $user->loan_amount);
            $interestRate = $request->get('interest_rate', $user->interest_rate);
            $loanTerm = $request->get('loan_term', $user->loan_term);
            $extraPayment = (float)$request->get('extra_payment', 0);

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

            return $this->respond([
                'status_code' => 200,
                'old_monthly_payment' => $oldMonthlyPayment,
                'new_monthly_payment' => $newMonthlyPayment,
                'number_of_payments' => $numberOfPayments,
                'total_interest_no_extra_payments' => $totalInterestNoExtraPayments,
                'total_interest_extra_payments' => $totalInterestExtraPayments,
                'refi_and_Save' => $refiAndSave,
                'year_saved' => $yearSaved,
                'loan_amount' => $loanAmount,
                'loan_term' => $loanTerm,
                'interest_rate' => $interestRate,
            ]);

        } catch(Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

    public function paymentCalculator(Request $request)
    {
        try {
            /** Set Value */
            $user = Auth::guard('api')->user();
            $loanAmount = $request->get('loan_amount', $user->loan_amount);
            $interestRate = $request->get('interest_rate', $user->interest_rate);
            $loanTerm = $request->get('loan_term', $user->loan_term);
            $extraPayment = (float)$request->get('extra_payment', 0);
            $currentMonth = (float)$request->get('current_month_payment', 1);

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

            return $this->respond([
                'status_code' => 200,
                'loan_amount' => (float)$loanAmount,
                'loan_term' => $loanTerm,
                'current_month_payment' => $currentMonth,
                'interest_rate' => (float)$interestRate,
                'interest_payment' => $interestPayment,
                'principal_payment' => $principalPayment,
                'monthly_payment' => $currentMonthlyPayment
            ]);
        } catch(Exception $e) {
            return $this->respondWithError($e->getMessage());
        }
    }

}