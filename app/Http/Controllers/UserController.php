<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function loadAcctgPage()
    {
        return view('acctg.main');
    }

    public function loadAssemblePage()
    {
        return view('production.main');
    }
}
