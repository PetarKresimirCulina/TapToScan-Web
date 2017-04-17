<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchas extends Model
{
    protected $fillable = ['user_id', 'product', 'amount', 'stripe_transaction_id'];
	
	
}
