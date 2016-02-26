<?php namespace App\Http\Controllers\APIs;

use App\Repositories\BankRepository;

class BankController extends APIController {

    protected $bank;

    public function __construct(BankRepository $bank)
    {
        $this->bank = $bank;
    }

    public function listAll()
    {
        $data = $this->bank->all();

        if( !$data ) {

            return $this->respondNotFound("Don't have any banks");
        }

        return $this->respond([
            'data' => $data->toArray()
        ]);
    }

}