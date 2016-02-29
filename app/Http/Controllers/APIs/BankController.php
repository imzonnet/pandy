<?php namespace App\Http\Controllers\APIs;

use App\Libs\BankHelper;
use App\Repositories\BankRepository;
use App\Transforms\BankTransformer;
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
        $banks = $this->bank->all();

        if( !$banks ) {
            return $this->respondNotFound("Don't have any banks");
        }

        return $this->respond(
            $this->bankTransformer->transformCollection($banks)
        );
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

    public function getBankSave(Request $request)
    {
        $user = Auth::guard('api')->user();
        $loanAmount = $request->get('loan_amount', $user->loan_amount);
        $interestRate = $request->get('interest_rate', $user->interest_rate);
        $loanTerm = $request->get('loan_term', $user->loan_term);

        $oldMonthlyPayment = $this->bankHelper->monthlyPayment($interestRate, $loanTerm, $loanAmount);
        $newMonthlyPayment = (float)$oldMonthlyPayment + (float)$request->get('extra_payment', 127) ;
        $numberOfPayments = $this->bankHelper->numberOfPayments($interestRate, $newMonthlyPayment, $loanAmount);

    }

    /**
     * Get Old Payment Save
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOldPaymentSave()
    {
        return $this->respond((float)$this->bankHelper->monthlyPayment());
    }

    /**
     * Get New Payment Save
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNewPaymentSave(Request $request)
    {
        $new = $this->bankHelper->monthlyPayment() + $request->get('extra_payment');
        return $this->respond((float)$new);
    }

    public function getMonthPayment(Request $request) {
        return $this->getNewPaymentSave($request);
    }


}