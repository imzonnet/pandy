<?php namespace App\Transforms;


use Carbon\Carbon;

class BankTransformer extends Transformer
{

    public function transform(array $bank)
    {
        $updatedDate = Carbon::parse($bank['updated_at']);
        $lastUpdate = Carbon::now()->diffInDays($updatedDate) . ' day';
        $lastUpdate .= $lastUpdate > 1 ? 's' : '';
        return [
            "id" =>  $bank['id'],
            "name" => $bank['name'],
            "interest_rate" => $bank['interest_rate'],
            "comparison_rate" => $bank['comparison_rate'],
            "annual_fees" => $bank['annual_fees'],
            "monthly_fees" => $bank['monthly_fees'],
            "special" => $bank['special'],
            "last_update" => $lastUpdate
        ];
    }
}