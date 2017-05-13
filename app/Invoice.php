<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function create($userID, $vatRate, $totalNet, $totalWVat, $sv, $so) {
		$this->userID = $userID;
		$this->vatRate = $vatRate;
		$this->totalNet = $totalNet;
		$this->totalWVat = $totalWVat;
		$this->taxExempt = env('TAX_EXEMPT');
		$this->zki = 'abcdefgh';
		$this->jir = 'bdefghijkl';
		$this->saleVenue = $sv;
		$this->saleOperator = $so;
		
		return $this->save();
	}
}
