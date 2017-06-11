<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagOrder extends Model
{
	
	public function invoice() {
		return $this->belongsTo('App\Invoice', 'invoice_id');
	}
	
    public function createOrder($userID, $address, $zip, $city, $country, $quantity, $paid, $shipped, $trackingID, $fullName, $invoiceID) {
		
		$this->user_id = $userID;
		$this->shipping_address = $address;
		$this->shipping_zip = $zip;
		$this->shipping_city = $city;
		$this->shipping_country = $country;
		$this->quantity = $quantity;
		$this->paid = $paid;
		$this->shipped = $shipped;
		$this->tracking_id = $trackingID;
		$this->shipping_name = $fullName;
		$this->invoice_id = $invoiceID;
		return $this->save();
		
	}
}
