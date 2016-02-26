<?php namespace App\Repositories;
use App\Bank;

class EloquentBankRepository extends EloquentBaseRepository implements BankRepository
{
    /**
     * @var $model
     */
    protected $model;

    /**
     * @param $model
     */
    public function __construct(Bank $model)
    {
        $this->model = $model;
    }

}