<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App;
use App\User;
use App\Plan;


class PaymentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

}
