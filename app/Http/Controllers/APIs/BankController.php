<?php namespace App\Http\Controllers\APIs;

use App\Repositories\BankRepository;
use App\Transforms\BankTransformer;

class BankController extends APIController {

    protected $bank;
    protected $bankTransformer;

    public function __construct(BankRepository $bank, BankTransformer $bankTransformer)
    {
        $this->bank = $bank;
        $this->bankTransformer = $bankTransformer;
    }

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

    public function getBank($id)
    {
        $bank = $this->bank->find($id);

        if( !$bank ) {
            return $this->respondNotFound("The bank not found");
        }

        return $this->respond(
            $this->bankTransformer->transform($bank->toArray())
        );
    }

}