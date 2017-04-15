<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// not used anywhere atm
class LocalizationController extends Controller
{
    public function index(Request $request){
        if($request->lang <> ''){
            app()->setLocale($request->lang);
        }
    }
}
