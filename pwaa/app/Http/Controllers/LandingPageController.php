<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    //

    public function index()
    {

        $data = Perawatan::get();
        
        return view('/landing_page', $data);
    }
}
