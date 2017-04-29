<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $primaryKey = 'id'; // or null

    public $incrementing = false;
}
