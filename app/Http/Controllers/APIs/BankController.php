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
        $data = $this->bank->all();

        if( !$data ) {
            return $this->respondNotFound("Don't have any banks");
        }

        return $this->respond(
            $this->bankTransformer->transformCollection($data)
        );
    }

}