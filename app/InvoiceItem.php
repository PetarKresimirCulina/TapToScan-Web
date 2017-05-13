<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    public function create($invoiceId, $description, $price, $currency, $quantity) {
		$this->invoiceId = $invoiceId;
		$this->description = $description;
		$this->price = $price;
		$this->currency = $currency;
		$this->quantity = $quantity;
		
		return $this->save();
	}
}
