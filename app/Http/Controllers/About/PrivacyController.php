<?php

namespace App\Http\Controllers\About;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App;

class PrivacyController extends Controller
{
    /**
     * Display a listing of the termofservice page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('about.privacy');
    }
}