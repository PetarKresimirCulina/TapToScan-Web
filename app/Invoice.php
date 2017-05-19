<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	
	public function user() {
		return $this->belongsTo('App\User', 'userID', 'id');
	}
	
	public function getInvoiceItems() {
		return $this->hasMany('App\InvoiceItem', 'invoiceId');
	}
	
	public function getCurrency() {
		return $this->belongsTo('App\Currency', 'currencyID', 'id');
	}
	
    public function create($userID, $vatRate, $totalNet, $totalWVat, $sv, $so, $ci) {
		$this->userID = $userID;
		$this->vatRate = $vatRate;
		$this->totalNet = $totalNet;
		$this->totalWVat = $totalWVat;
		$this->taxExempt = env('TAX_EXEMPT');
		$this->zki = 'abcdefgh';
		$this->jir = 'bdefghijkl';
		$this->saleVenue = $sv;
		$this->saleOperator = $so;
		$this->currencyID = $ci;
		
		$this->save();
		return $this;
	}
}
